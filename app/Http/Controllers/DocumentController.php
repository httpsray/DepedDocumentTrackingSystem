<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Office;
use App\Models\RoutingLog;
use App\Services\ReferenceNumberService;
use App\Services\TrackingNumberService;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct(
        private TrackingNumberService $trackingNumberService,
        private ReferenceNumberService $referenceNumberService
    ) {}

    /**
     * Submit a new document entry (metadata only, no file uploads).
     * Public - no login required.
     */
    public function submit(Request $request)
    {
        $isAuth = auth()->check();

        $rules = [
            'type' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
        ];

        if (!$isAuth) {
            $rules['sender_first_name'] = ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s.\-]+$/'];
            $rules['sender_last_name']  = ['required', 'string', 'max:100', 'regex:/^[a-zA-Z\s.\-]+$/'];
            $rules['sender_contact'] = 'nullable|string|max:20';
            $rules['sender_email'] = 'required|email|max:255';
        }

        $request->validate($rules, [
            'sender_first_name.regex' => 'First name may only contain letters, spaces, dots, and hyphens.',
            'sender_last_name.regex'  => 'Last name may only contain letters, spaces, dots, and hyphens.',
            'sender_email.required'   => 'Email address is required so your submitted documents can be linked to your account later.',
        ]);

        if ($isAuth) {
            $authUser = auth()->user();
            if ($authUser->isRepresentative() && str_contains($authUser->name, ' - ')) {
                $parts = explode(' - ', $authUser->name, 2);
                $senderName = $parts[1];
            } else {
                $senderName = $authUser->name;
            }
            $senderEmail = $authUser->email;
            $senderContact = $request->sender_contact ?? null;
        } else {
            $senderName = trim($request->sender_first_name) . ' ' . trim($request->sender_last_name);
            $senderEmail = strtolower(trim((string) $request->sender_email));
            $senderContact = $request->sender_contact;
        }

        try {
            $normalizedSubject = strtolower(trim((string) $request->subject));
            $normalizedType = strtolower(trim((string) $request->type));
            $normalizedDescription = strtolower(trim((string) ($request->description ?? '')));

            $recentDuplicateQuery = Document::query()
                ->whereRaw('LOWER(TRIM(subject)) = ?', [$normalizedSubject])
                ->whereRaw('LOWER(TRIM(type)) = ?', [$normalizedType])
                ->where('created_at', '>=', now()->subMinutes(10))
                ->whereIn('status', ['submitted', 'received', 'in_review', 'on_hold', 'for_pickup']);

            if ($normalizedDescription !== '') {
                $recentDuplicateQuery->whereRaw("LOWER(TRIM(COALESCE(description, ''))) = ?", [$normalizedDescription]);
            } else {
                $recentDuplicateQuery->where(function ($q) {
                    $q->whereNull('description')
                      ->orWhereRaw("TRIM(COALESCE(description, '')) = ''");
                });
            }

            if ($isAuth && auth()->id()) {
                $recentDuplicateQuery->where('user_id', auth()->id());
            } else {
                $recentDuplicateQuery
                    ->whereRaw("LOWER(TRIM(COALESCE(sender_email, ''))) = ?", [strtolower(trim((string) $senderEmail))])
                    ->whereRaw("LOWER(TRIM(COALESCE(sender_name, ''))) = ?", [strtolower(trim((string) $senderName))]);
            }

            $recentDuplicate = $recentDuplicateQuery->latest('created_at')->first();

            $recordsOffice = Office::query()
                ->whereRaw('UPPER(code) = ?', ['RECORDS'])
                ->where('is_active', true)
                ->first();

            if (!$recordsOffice) {
                $recordsOffice = Office::query()
                    ->whereRaw('LOWER(name) = ?', ['records section'])
                    ->where('is_active', true)
                    ->first();
            }

            if (!$recordsOffice) {
                return response()->json([
                    'success' => false,
                    'message' => 'Records Section office is not configured. Please contact the administrator.',
                ], 500);
            }

            if ($recentDuplicate) {
                return response()->json([
                    'success' => true,
                    'duplicate' => true,
                    'message' => 'A similar document was already submitted recently. Reusing the existing reference number.',
                    'reference_number' => $recentDuplicate->reference_number,
                    'tracking_number' => $recentDuplicate->tracking_number,
                    'details' => [
                        'sender_name'  => $recentDuplicate->sender_name,
                        'type'         => $recentDuplicate->type,
                        'subject'      => $recentDuplicate->subject,
                        'description'  => $recentDuplicate->description ?: 'No remarks provided',
                        'submitted_to' => $recordsOffice->name,
                        'date'         => $recentDuplicate->created_at->setTimezone('Asia/Manila')->format('M d, Y â€” h:i A'),
                    ],
                ]);
            }

            $result = $this->trackingNumberService->generate();
            $referenceNumber = $this->referenceNumberService->generateUnique();

            $document = new Document([
                'submitted_to_office_id' => $recordsOffice->id,
                'subject' => $request->subject,
                'type' => $request->type,
                'sender_name' => $senderName,
                'sender_contact' => $senderContact,
                'sender_email' => $senderEmail,
                'description' => $request->description,
            ]);
            $document->tracking_number = $result['tracking_number'];
            $document->reference_number = $referenceNumber;
            $document->user_id = auth()->id();
            $document->current_office_id = null;
            $document->current_handler_id = null;
            $document->status = 'submitted';
            $document->last_action_at = now();
            $document->save();

            RoutingLog::create([
                'document_id' => $document->id,
                'performed_by' => auth()->id(),
                'from_office_id' => null,
                'to_office_id' => null,
                'action' => 'submitted',
                'status_after' => 'submitted',
                'remarks' => 'Document submitted. Awaiting acceptance by Records Section.',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document submitted successfully!',
                'reference_number' => $document->reference_number,
                'tracking_number' => $document->tracking_number,
                'details' => [
                    'sender_name'  => $document->sender_name,
                    'type'         => $document->type,
                    'subject'      => $document->subject,
                    'description'  => $document->description ?: 'No remarks provided',
                    'submitted_to' => $recordsOffice->name,
                    'date'         => $document->created_at->setTimezone('Asia/Manila')->format('M d, Y — h:i A'),
                ],
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Document submission failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit document. Please try again later.',
            ], 500);
        }
    }

    /**
     * Track a document by its tracking number - public.
     * Returns full routing log timeline.
     */
    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'nullable|string',
            'reference_number' => 'nullable|string',
        ]);

        $lookupInput = strtoupper(trim(strip_tags((string)($request->tracking_number ?: $request->reference_number))));

        if ($lookupInput === '') {
            return response()->json([
                'success' => false,
                'message' => 'Tracking number is required.',
            ], 422);
        }

        $document = Document::with([
            'submittedToOffice',
            'currentOffice',
            'currentHandler',
            'routingLogs.fromOffice',
            'routingLogs.toOffice',
            'routingLogs.performer',
        ])->where(function ($q) use ($lookupInput) {
            $q->whereRaw('UPPER(reference_number) = ?', [$lookupInput])
              ->orWhereRaw('UPPER(tracking_number) = ?', [$lookupInput]);
        })->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Tracking number not found. Please check and try again.',
            ], 404);
        }

        $orderedLogs = $document->routingLogs->sortBy('created_at')->values();
        $timelineNow = now();
        $logCount = $orderedLogs->count();
        $submittedOfficeName = $document->submittedToOffice?->name ?: 'Records Section';

        // Build office segments to derive clear time-in/time-out per office
        // plus transfer duration between offices.
        $segments = [];

        for ($i = 0; $i < $logCount; $i++) {
            $log = $orderedLogs->get($i);
            $isSubmissionPending = $log->action === 'submitted' && $log->status_after === 'submitted';
            $officeId = null;
            if (!$isSubmissionPending) {
                // Forwarding is an action done by the source office (time-out point).
                if ($log->action === 'forwarded' && $log->from_office_id) {
                    $officeId = $log->from_office_id;
                } else {
                    $officeId = $log->to_office_id ?: $log->from_office_id;
                }
            }
            if (!$officeId) {
                continue;
            }

            if (empty($segments) || $segments[array_key_last($segments)]['office_id'] !== $officeId) {
                $segments[] = [
                    'office_id' => $officeId,
                    'start_index' => $i,
                    'end_index' => $i,
                ];
            } else {
                $segments[array_key_last($segments)]['end_index'] = $i;
            }
        }

        $officeNameMap = [];
        if (!empty($segments)) {
            $officeIds = array_values(array_unique(array_map(fn ($seg) => $seg['office_id'], $segments)));
            $officeNameMap = Office::query()
                ->whereIn('id', $officeIds)
                ->pluck('name', 'id')
                ->all();
        }

        $arrivalMetaByLogIndex = [];
        $segmentCount = count($segments);
        for ($segIndex = 0; $segIndex < $segmentCount; $segIndex++) {
            $segment = $segments[$segIndex];
            $nextSegment = $segments[$segIndex + 1] ?? null;

            $startLog = $orderedLogs->get($segment['start_index']);
            $timeInAt = $startLog->created_at;
            // Time-out = when the next office received the document (the only log they create).
            // There are no separate "forwarded" logs, so using endLog->created_at equals timeInAt
            // for single-log segments, giving 0s. Use the next segment's start timestamp instead.
            $nextInAt = $nextSegment ? $orderedLogs->get($nextSegment['start_index'])->created_at : null;
            $timeOutAt = $nextInAt; // null when this is the current/last office (open segment)

            $officeDurationSeconds = $nextInAt !== null
                ? max(0, $timeInAt->diffInSeconds($nextInAt))
                : max(0, $timeInAt->diffInSeconds($timelineNow));

            // Between-offices transit time is not tracked (no forwarded logs), so always null.
            $betweenOfficesSeconds = null;

            $arrivalMetaByLogIndex[$segment['start_index']] = [
                'office_name' => $officeNameMap[$segment['office_id']] ?? 'Office',
                'time_in_at' => $timeInAt,
                'time_out_at' => $timeOutAt,
                'office_duration_seconds' => $officeDurationSeconds,
                'between_offices_seconds' => $betweenOfficesSeconds,
                'next_office_name' => $nextSegment
                    ? ($officeNameMap[$nextSegment['office_id']] ?? 'Next Office')
                    : null,
            ];
        }

        $logs = $orderedLogs->map(function ($log, $index) use ($submittedOfficeName, $arrivalMetaByLogIndex) {
            $isSubmissionPending = $log->action === 'submitted' && $log->status_after === 'submitted';
            $arrivalMeta = $arrivalMetaByLogIndex[$index] ?? null;
            $officeDurationSeconds = $arrivalMeta['office_duration_seconds'] ?? null;
            $betweenOfficesSeconds = $arrivalMeta['between_offices_seconds'] ?? null;

            $remarks = $log->remarks;
            $displayToOffice = $isSubmissionPending ? ($log->toOffice?->name ?: $submittedOfficeName) : $log->toOffice?->name;
            if ($isSubmissionPending) {
                $remarks = 'Document submitted. Awaiting acceptance by ' . $displayToOffice . '.';
            }

            return [
                'id' => $log->id,
                'action' => $log->action,
                'action_label' => $log->actionLabel(),
                'status_after' => $log->status_after,
                'from_office' => $isSubmissionPending ? null : $log->fromOffice?->name,
                'to_office' => $displayToOffice,
                'performed_by' => $log->performer
                    ? (str_contains($log->performer->name, ' - ')
                        ? trim(substr($log->performer->name, strpos($log->performer->name, ' - ') + 3))
                        : $log->performer->name)
                    : null,
                'remarks' => $remarks,
                'timestamp' => $log->created_at->copy()->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
                'office_name' => $arrivalMeta['office_name'] ?? null,
                'office_time_in' => $arrivalMeta
                    ? $arrivalMeta['time_in_at']->copy()->setTimezone('Asia/Manila')->format('M d, Y h:i A')
                    : null,
                'office_time_out' => ($arrivalMeta && $arrivalMeta['time_out_at'])
                    ? $arrivalMeta['time_out_at']->copy()->setTimezone('Asia/Manila')->format('M d, Y h:i A')
                    : null,
                'office_duration_human' => $officeDurationSeconds !== null
                    ? $this->formatDuration((int) $officeDurationSeconds)
                    : null,
                'office_duration_secs' => $officeDurationSeconds,
                'between_offices_human' => $betweenOfficesSeconds !== null
                    ? $this->formatDuration((int) $betweenOfficesSeconds)
                    : null,
                'between_offices_secs' => $betweenOfficesSeconds,
                'next_office' => $arrivalMeta['next_office_name'] ?? null,
            ];
        });

        $isSubmittedAwaitingAcceptance = $document->status === 'submitted';
        $currentOfficeName = $isSubmittedAwaitingAcceptance
            ? $submittedOfficeName
            : ($document->currentOffice?->name ?: $submittedOfficeName);
        $currentHandlerName = $isSubmittedAwaitingAcceptance ? null : $document->currentHandler?->name;

        return response()->json([
            'success' => true,
            'document' => [
                'reference_number' => $document->reference_number ?: $document->tracking_number,
                'tracking_number' => $document->tracking_number,
                'subject' => $document->subject,
                'type' => $document->type,
                'status' => $document->status,
                'status_label' => $document->statusLabel(),
                'status_color' => $document->statusColor(),
                'sender_name' => $document->sender_name,
                'submitted_to_office' => $document->submittedToOffice?->name,
                'current_office' => $currentOfficeName,
                'current_handler' => $currentHandlerName,
                'last_action_at' => $document->last_action_at?->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
                'submitted_at' => $document->created_at->setTimezone('Asia/Manila')->format('M d, Y h:i A'),
                'routing_logs' => $logs,
            ],
        ]);
    }

    /**
     * Generate QR code SVG for a tracking number.
     */
    public function qrCode(string $tracking)
    {
        $tracking = strtoupper(trim(strip_tags($tracking)));

        $document = Document::where(function ($q) use ($tracking) {
            $q->whereRaw('UPPER(tracking_number) = ?', [$tracking])
              ->orWhereRaw('UPPER(reference_number) = ?', [$tracking]);
        })->first();

        if (!$document) {
            abort(404);
        }

        $options = new QROptions([
            'outputType'   => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'     => QRCode::ECC_M,
            'scale'        => 10,
            'addQuietzone' => true,
            'imageBase64'  => false,
        ]);

        $receiveUrl = url('/receive/' . $document->tracking_number);
        $svg = (new QRCode($options))->render($receiveUrl);

        return response($svg, 200, [
            'Content-Type'  => 'image/svg+xml',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    private function formatDuration(int $seconds): string
    {
        if ($seconds < 60) {
            return $seconds . 's';
        }

        $days = intdiv($seconds, 86400);
        $seconds %= 86400;

        $hours = intdiv($seconds, 3600);
        $seconds %= 3600;

        $minutes = intdiv($seconds, 60);
        $seconds %= 60;

        $parts = [];

        if ($days > 0) {
            $parts[] = $days . 'd';
        }

        if ($hours > 0) {
            $parts[] = $hours . 'h';
        }

        if ($minutes > 0) {
            $parts[] = $minutes . 'm';
        }

        if (!$parts) {
            $parts[] = $seconds . 's';
        }

        return implode(' ', array_slice($parts, 0, 3));
    }
}

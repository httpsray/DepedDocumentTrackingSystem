<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use App\Services\TrackingNumberService;

class DocumentController extends Controller
{
    public function __construct(
        private TrackingNumberService $trackingNumberService
    ) {}

    /**
     * Submit a new document and generate tracking number.
     */
    public function submit(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
        ]);

        try {
            // Generate concurrency-safe tracking number
            // (uses its own DB transaction with row locking)
            $result = $this->trackingNumberService->generate();

            // Create Document (attach to logged-in user if available)
            $document = Document::create([
                'tracking_number'  => $result['tracking_number'],
                'office_code'      => $result['office_code'],
                'user_id'          => auth()->id(),
                'subject'          => $request->subject,
                'type'             => $request->type ?? 'General',
                'status'           => 'received',
                'sender_name'      => $request->sender_name,
                'sender_office'    => $request->sender_office,
                'description'      => $request->description,
                'recipient_office' => $request->recipient_office ?? 'Records',
            ]);

            return response()->json([
                'success'         => true,
                'message'         => 'Document submitted successfully!',
                'tracking_number' => $result['tracking_number'],
                'data'            => $document,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit document: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Track a document by its tracking number.
     */
    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string',
        ]);

        $document = Document::where('tracking_number', $request->tracking_number)->first();

        if (!$document) {
            return response()->json([
                'success' => false,
                'message' => 'Reference number not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $document,
        ]);
    }
}

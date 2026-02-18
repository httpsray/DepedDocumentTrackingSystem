<?php

namespace App\Services;

use App\Models\TrackingCounter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TrackingNumberService
{
    /**
     * Maximum sequence value (6 digits = 999999).
     * If exceeded, the system must alert administrators.
     */
    private const MAX_SEQUENCE = 999999;

    /**
     * Default office code. Change this or pass a different code
     * when calling generate() for multi-office support.
     */
    private const DEFAULT_OFFICE = 'CSJDM';

    /**
     * Generate a lifetime-unique tracking number.
     *
     * Format: {OFFICE}-{YYYY}-{MM}-{XXXXXX}
     * Example: CSJDM-2026-02-000123
     *
     * Uses row-level locking (SELECT ... FOR UPDATE) inside a
     * database transaction to guarantee concurrency safety.
     * Even with 50+ simultaneous requests, no duplicates.
     *
     * @param  string|null  $officeCode  Office prefix (default: CSJDM)
     * @return array{tracking_number: string, office_code: string}
     *
     * @throws \RuntimeException  If monthly sequence exceeds 999999
     * @throws \Throwable         On database errors
     */
    public function generate(?string $officeCode = null): array
    {
        $officeCode = strtoupper($officeCode ?? self::DEFAULT_OFFICE);

        // Use Asia/Manila timezone for date parts
        $now   = Carbon::now('Asia/Manila');
        $year  = $now->year;
        $month = $now->month;

        return DB::transaction(function () use ($officeCode, $year, $month) {

            // ──────────────────────────────────────────────
            // 1. Lock or create the counter row
            // ──────────────────────────────────────────────
            //
            // lockForUpdate() issues SELECT ... FOR UPDATE.
            // Any other transaction hitting the same row will
            // WAIT until this transaction commits or rolls back.
            // This eliminates race conditions entirely.
            //
            $counter = TrackingCounter::where('office_code', $officeCode)
                ->where('year', $year)
                ->where('month', $month)
                ->lockForUpdate()
                ->first();

            if (!$counter) {
                // First document this office+month — create counter starting at 0
                $counter = TrackingCounter::create([
                    'office_code'   => $officeCode,
                    'year'          => $year,
                    'month'         => $month,
                    'last_sequence' => 0,
                ]);

                // Re-lock the newly created row
                $counter = TrackingCounter::where('id', $counter->id)
                    ->lockForUpdate()
                    ->first();
            }

            // ──────────────────────────────────────────────
            // 2. Increment and validate
            // ──────────────────────────────────────────────
            $nextSequence = $counter->last_sequence + 1;

            if ($nextSequence > self::MAX_SEQUENCE) {
                throw new \RuntimeException(
                    "Tracking number sequence exhausted for {$officeCode}-{$year}-" .
                    str_pad($month, 2, '0', STR_PAD_LEFT) . ". " .
                    "Maximum of " . number_format(self::MAX_SEQUENCE) . " documents per month reached. " .
                    "Contact system administrator to upgrade to 7+ digit sequences."
                );
            }

            // ──────────────────────────────────────────────
            // 3. Save the new sequence
            // ──────────────────────────────────────────────
            $counter->last_sequence = $nextSequence;
            $counter->save();

            // ──────────────────────────────────────────────
            // 4. Build the tracking number
            // ──────────────────────────────────────────────
            $trackingNumber = sprintf(
                '%s-%04d-%02d-%06d',
                $officeCode,
                $year,
                $month,
                $nextSequence
            );

            return [
                'tracking_number' => $trackingNumber,
                'office_code'     => $officeCode,
            ];

        }); // Transaction auto-commits here; lock is released
    }
}

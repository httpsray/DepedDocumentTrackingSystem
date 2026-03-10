<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Models\RoutingLog;
use Illuminate\Console\Command;

class ArchiveUnprocessedDocuments extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'documents:archive-unprocessed {--days=7 : Number of days before archiving}';

    /**
     * The console command description.
     */
    protected $description = 'Archive documents that remain in "submitted" status for more than 7 days (unprocessed).';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $documents = Document::where('status', 'submitted')
            ->where('created_at', '<=', $cutoff)
            ->get();

        if ($documents->isEmpty()) {
            $this->info('No unprocessed documents found older than ' . $days . ' days.');
            return 0;
        }

        $count = 0;
        foreach ($documents as $document) {
            $document->status         = 'archived';
            $document->archived_at    = now();
            $document->last_action_at = now();
            $document->save();

            RoutingLog::create([
                'document_id'    => $document->id,
                'performed_by'   => null,
                'from_office_id' => null,
                'to_office_id'   => null,
                'action'         => 'archived',
                'status_after'   => 'archived',
                'remarks'        => "Auto-archived: Document was not received within {$days} days of submission.",
            ]);

            $count++;
        }

        $this->info("Archived {$count} unprocessed document(s).");

        return 0;
    }
}

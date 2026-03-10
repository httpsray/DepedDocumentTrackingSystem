<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Document extends Model
{
    use HasFactory;

    /**
     * Clear stats caches when any document is created, updated, or deleted.
     * Keeps cached stats endpoints in sync with the database.
     */
    protected static function booted(): void
    {
        $bustCache = function (Document $doc) {
            // Global caches
            Cache::forget('admin_stats');
            Cache::forget('records_stats');

            // Per-user cache (submitter)
            if ($doc->user_id) {
                Cache::forget('user_stats_' . $doc->user_id);
            }

            // Per-office cache (current office)
            if ($doc->current_office_id) {
                Cache::forget('office_stats_' . $doc->current_office_id);
            }

            // Per-office cache (submitted-to office)
            if ($doc->submitted_to_office_id) {
                Cache::forget('office_stats_' . $doc->submitted_to_office_id);
            }

            // ICT handler cache
            if ($doc->current_handler_id) {
                Cache::forget('ict_stats_' . $doc->current_handler_id);
            }
        };

        static::created($bustCache);
        static::updated($bustCache);
        static::deleted($bustCache);
    }

    protected $fillable = [
        'submitted_to_office_id',
        'subject',
        'type',
        'sender_name',
        'sender_contact',
        'sender_email',
        'sender_office',
        'recipient_office',
        'description',
    ];

    /**
     * Attributes guarded from mass assignment.
     * tracking_number, reference_number, user_id, status, current_office_id,
     * current_handler_id, last_action_at, archived_at are set explicitly.
     */
    protected $guarded = [
        'id',
        'tracking_number',
        'reference_number',
        'user_id',
        'current_office_id',
        'current_handler_id',
        'status',
        'last_action_at',
        'archived_at',
    ];

    protected $casts = [
        'last_action_at' => 'datetime',
        'archived_at'    => 'datetime',
    ];

    // ─── Status constants ───
    const STATUSES = [
        'submitted'  => 'Submitted',
        'received'   => 'Received',
        'in_review'  => 'Processing',
        'on_hold'    => 'On Hold',
        'completed'  => 'Completed',
        'for_pickup' => 'For Pickup',
        'returned'   => 'Returned',
        'cancelled'  => 'Cancelled',
        'archived'   => 'Archived',
    ];

    const STATUS_COLORS = [
        'submitted'  => '#2563eb',
        'received'   => '#0891b2',
        'in_review'  => '#7c3aed',
        'on_hold'    => '#f59e0b',
        'completed'  => '#16a34a',
        'for_pickup' => '#ea580c',
        'returned'   => '#dc2626',
        'cancelled'  => '#6b7280',
        'archived'   => '#9ca3af',
    ];

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function statusColor(): string
    {
        return self::STATUS_COLORS[$this->status] ?? '#64748b';
    }

    // ─── Relationships ───

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submittedToOffice()
    {
        return $this->belongsTo(Office::class, 'submitted_to_office_id');
    }

    public function currentOffice()
    {
        return $this->belongsTo(Office::class, 'current_office_id');
    }

    public function currentHandler()
    {
        return $this->belongsTo(User::class, 'current_handler_id');
    }

    public function routingLogs()
    {
        return $this->hasMany(RoutingLog::class)->orderBy('created_at', 'asc');
    }
}

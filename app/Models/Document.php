<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'tracking_number',
        'office_code',
        'user_id',
        'subject',
        'type',
        'status',
        'sender_name',
        'sender_office',
        'recipient_office',
        'description',
        'files',
    ];

    /**
     * The user who submitted this document.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'files' => 'array'
    ];
}

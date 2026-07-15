<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Reminder extends Model
{
    protected $fillable = [
        'target_type',
        'target_id',
        'user_id',
        'next_at',
        'sent_count',
        'acknowledged_at',
        'meta',
    ];

    protected $casts = [
        'next_at' => 'datetime',
        'acknowledged_at' => 'datetime',
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isAcknowledged(): bool
    {
        return filled($this->acknowledged_at);
    }

    public function due(): bool
    {
        return $this->next_at && $this->next_at->lte(now());
    }
}

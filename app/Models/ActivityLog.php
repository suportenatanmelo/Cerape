<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'module',
        'action',
        'description',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'ip',
        'browser',
        'platform',
        'device',
        'url',
        'method',
        'session_id',
        'executed_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'executed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

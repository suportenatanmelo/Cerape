<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = 'activity_logs';

    /** @var array<string> */
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

    /** @var array<string, string> */
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

    public function scopeLatestExecuted($query)
    {
        return $query->orderByDesc('executed_at')->orderByDesc('id');
    }

    public function scopeModule($query, ?string $module)
    {
        if ($module === null) {
            return $query;
        }

        return $query->where('module', $module);
    }

    public function scopeAction($query, ?string $action)
    {
        if ($action === null) {
            return $query;
        }

        return $query->where('action', $action);
    }

    public function scopeForUser($query, ?int $userId)
    {
        if ($userId === null) {
            return $query;
        }

        return $query->where('user_id', $userId);
    }
}

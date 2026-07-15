<?php

namespace App\Services;

use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Model;

class AuditService
{
    public function __construct(private readonly ActivityLogger $logger)
    {
    }

    /**
     * Log a generic audit entry.
     *
     * @param  string  $module
     * @param  string  $action
     * @param  string|null  $description
     * @param  Model|null  $model
     * @param  array<string,mixed>  $oldValues
     * @param  array<string,mixed>  $newValues
     */
    public function log(string $module, string $action, ?string $description = null, ?Model $model = null, array $oldValues = [], array $newValues = []): void
    {
        $this->logger->custom(
            $module,
            $action,
            $description ?? '',
            $model,
            $oldValues,
            $newValues
        );
    }

    /**
     * Shortcut to log model events.
     */
    public function logModel(string $action, Model $model, ?string $module = null, ?string $description = null): void
    {
        match ($action) {
            'create' => $this->logger->modelCreated($model, $module, $description),
            'update' => $this->logger->modelUpdated($model, $module, $description),
            'delete' => $this->logger->modelDeleted($model, $module, $description),
            'restore' => $this->logger->modelRestored($model, $module, $description),
            'force_delete' => $this->logger->modelForceDeleted($model, $module, $description),
            default => $this->logger->custom($module ?? 'Geral', $action, $description ?? '', $model),
        };
    }
}

<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Support\ActivityLogger;
use App\Support\Concerns\HasActivityLogs;
use Illuminate\Database\Eloquent\Model;

class ActivityLogObserver
{
    public function __construct(private readonly ActivityLogger $logger)
    {
    }

    public function created(Model $model): void
    {
        $this->log('create', $model);
    }

    public function updated(Model $model): void
    {
        $this->log('update', $model);
    }

    public function deleted(Model $model): void
    {
        $this->log('delete', $model);
    }

    public function restored(Model $model): void
    {
        $this->log('restore', $model);
    }

    public function forceDeleted(Model $model): void
    {
        $this->log('force_delete', $model);
    }

    private function log(string $action, Model $model): void
    {
        if ($model instanceof ActivityLog) {
            return;
        }

        if (! $this->shouldObserve($model)) {
            return;
        }

        if ($action === 'create') {
            $this->logger->modelCreated($model);

            return;
        }

        if ($action === 'update') {
            $this->logger->modelUpdated($model);

            return;
        }

        if ($action === 'delete') {
            $this->logger->modelDeleted($model);

            return;
        }

        if ($action === 'restore') {
            $this->logger->modelRestored($model);

            return;
        }

        if ($action === 'force_delete') {
            $this->logger->modelForceDeleted($model);
        }
    }

    private function shouldObserve(Model $model): bool
    {
        if ($model instanceof ActivityLog) {
            return false;
        }

        return true;
    }
}

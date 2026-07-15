<?php

namespace App\Audit\Observers;

use App\Audit\Services\AuditService;
use App\Audit\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    public function __construct(private readonly AuditService $auditService)
    {
    }

    public function created(Model $model): void
    {
        if (! $this->shouldObserve($model)) {
            return;
        }

        $this->auditService->logCreate($model);
    }

    public function updated(Model $model): void
    {
        if (! $this->shouldObserve($model)) {
            return;
        }

        $this->auditService->logUpdate($model);
    }

    public function deleted(Model $model): void
    {
        if (! $this->shouldObserve($model)) {
            return;
        }

        $this->auditService->logDelete($model);
    }

    public function restored(Model $model): void
    {
        if (! $this->shouldObserve($model)) {
            return;
        }

        $this->auditService->logRestore($model);
    }

    public function forceDeleted(Model $model): void
    {
        if (! $this->shouldObserve($model)) {
            return;
        }

        $this->auditService->logForceDelete($model);
    }

    private function shouldObserve(Model $model): bool
    {
        return in_array(Auditable::class, class_uses_recursive($model), true);
    }
}

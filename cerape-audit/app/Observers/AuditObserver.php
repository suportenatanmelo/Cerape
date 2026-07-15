<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Services\AuditService;

class AuditObserver
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    public function created($model)
    {
        $this->auditService->created($model);
    }

    public function updated($model)
    {
        $this->auditService->updated($model);
    }

    public function deleted($model)
    {
        $this->auditService->deleted($model);
    }

    public function restored($model)
    {
        $this->auditService->restored($model);
    }

    public function forceDeleted($model)
    {
        $this->auditService->forceDeleted($model);
    }

    public function register()
    {
        // Register the observer for all models that use the Auditable trait
        foreach (get_declared_classes() as $class) {
            if (in_array('App\Traits\Auditable', class_uses($class))) {
                $class::observe($this);
            }
        }
    }
}
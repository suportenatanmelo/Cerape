<?php

namespace App\Traits;

use App\Services\AuditService;

trait Auditable
{
    protected static function bootAuditable()
    {
        static::created(function ($model) {
            AuditService::log()->created($model);
        });

        static::updated(function ($model) {
            AuditService::log()->updated($model);
        });

        static::deleted(function ($model) {
            AuditService::log()->deleted($model);
        });

        static::restored(function ($model) {
            AuditService::log()->restored($model);
        });

        static::forceDeleted(function ($model) {
            AuditService::log()->deleted($model);
        });
    }

    protected function getChanges()
    {
        $changes = [];
        foreach ($this->getDirty() as $key => $value) {
            if (!in_array($key, ['updated_at', 'created_at', 'remember_token'])) {
                $changes[$key] = [
                    'old' => $this->getOriginal($key),
                    'new' => $value,
                ];
            }
        }
        return $changes;
    }
}
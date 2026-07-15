<?php

namespace App\Audit\Traits;

use App\Audit\Services\AuditService;
use Illuminate\Database\Eloquent\Model;

trait Auditable
{
    protected static function bootAuditable(): void
    {
        static::created(function (Model $model): void {
            app(AuditService::class)->logCreate($model);
        });

        static::updated(function (Model $model): void {
            app(AuditService::class)->logUpdate($model);
        });

        static::deleted(function (Model $model): void {
            app(AuditService::class)->logDelete($model);
        });

        static::restored(function (Model $model): void {
            app(AuditService::class)->logRestore($model);
        });

        static::forceDeleted(function (Model $model): void {
            app(AuditService::class)->logForceDelete($model);
        });
    }
}

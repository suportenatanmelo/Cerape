<?php

namespace App\Audit\Services;

use App\Support\ActivityLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AuditService
{
    /**
     * @var array<int, string>
     */
    private const SENSITIVE_FIELDS = [
        'password',
        'remember_token',
        'api_token',
        'access_token',
        'refresh_token',
        'secret',
        'csrf_token',
    ];

    public function __construct(private readonly ActivityLogger $activityLogger)
    {
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function maskSensitiveValues(array $payload): array
    {
        foreach ($payload as $key => $value) {
            $normalizedKey = Str::of((string) $key)->lower()->toString();

            if (in_array($normalizedKey, self::SENSITIVE_FIELDS, true)) {
                $payload[$key] = '********';

                continue;
            }

            if (is_array($value)) {
                $payload[$key] = $this->maskSensitiveValues($value);
            }
        }

        return $payload;
    }

    public function logCreate(Model $model, ?string $module = null, ?string $description = null): void
    {
        $this->activityLogger->modelCreated($model, $module, $description);
    }

    public function logUpdate(Model $model, ?string $module = null, ?string $description = null): void
    {
        $this->activityLogger->modelUpdated($model, $module, $description);
    }

    public function logDelete(Model $model, ?string $module = null, ?string $description = null): void
    {
        $this->activityLogger->modelDeleted($model, $module, $description);
    }

    public function logRestore(Model $model, ?string $module = null, ?string $description = null): void
    {
        $this->activityLogger->modelRestored($model, $module, $description);
    }

    public function logForceDelete(Model $model, ?string $module = null, ?string $description = null): void
    {
        $this->activityLogger->modelForceDeleted($model, $module, $description);
    }
}

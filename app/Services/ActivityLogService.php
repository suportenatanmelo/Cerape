<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class ActivityLogService
{
    private const SENSITIVE_KEYS = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    public function record(
        string $module,
        string $action,
        ?string $description = null,
        ?Model $model = null,
        array $oldValues = [],
        array $newValues = [],
        ?User $user = null,
        array $context = [],
    ): ?ActivityLog {
        try {
            $request = request();

            $payload = array_merge([
                'user_id' => $user?->getKey() ?? Auth::id(),
                'module' => $module,
                'action' => $action,
                'description' => $description,
                'model_type' => $model ? $model::class : ($context['model_type'] ?? null),
                'model_id' => $model?->getKey() ?? ($context['model_id'] ?? null),
                'old_values' => $this->normalizeValue($oldValues),
                'new_values' => $this->normalizeValue($newValues),
                'ip' => $context['ip'] ?? $this->requestIp($request),
                'browser' => $context['browser'] ?? $this->detectBrowser($request),
                'platform' => $context['platform'] ?? $this->detectPlatform($request),
                'device' => $context['device'] ?? $this->detectDevice($request),
                'url' => $context['url'] ?? $this->requestUrl($request),
                'method' => $context['method'] ?? $this->requestMethod($request),
                'session_id' => $context['session_id'] ?? $this->requestSessionId($request),
                'executed_at' => $context['executed_at'] ?? now(),
            ], $context['extra'] ?? []);

            return ActivityLog::query()->create($payload);
        } catch (Throwable $e) {
            report($e);

            Log::warning('Falha ao registrar auditoria.', [
                'module' => $module,
                'action' => $action,
                'description' => $description,
                'model_type' => $model?->getMorphClass() ?? ($context['model_type'] ?? null),
                'model_id' => $model?->getKey() ?? ($context['model_id'] ?? null),
            ]);

            return null;
        }
    }

    public function recordModelCreated(Model $model, string $module, ?string $description = null): ?ActivityLog
    {
        return $this->record(
            module: $module,
            action: 'created',
            description: $description ?? $this->describeModel('criado', $model),
            model: $model,
            oldValues: [],
            newValues: $this->modelSnapshot($model),
        );
    }

    public function recordModelUpdated(Model $model, string $module, ?string $description = null): ?ActivityLog
    {
        $changes = $this->meaningfulChanges($model);

        if ($changes === []) {
            return null;
        }

        return $this->record(
            module: $module,
            action: 'updated',
            description: $description ?? $this->describeModel('atualizado', $model),
            model: $model,
            oldValues: $changes['old'],
            newValues: $changes['new'],
        );
    }

    public function recordModelDeleted(Model $model, string $module, ?string $description = null): ?ActivityLog
    {
        return $this->record(
            module: $module,
            action: 'deleted',
            description: $description ?? $this->describeModel('excluído', $model),
            model: $model,
            oldValues: $this->modelSnapshot($model),
            newValues: [],
        );
    }

    public function recordManual(
        string $module,
        string $action,
        string $description,
        ?Model $model = null,
        array $oldValues = [],
        array $newValues = [],
        array $context = [],
    ): ?ActivityLog {
        return $this->record($module, $action, $description, $model, $oldValues, $newValues, null, $context);
    }

    public function recordAuthentication(string $action, ?User $user = null, ?string $description = null, array $context = []): ?ActivityLog
    {
        return $this->record(
            module: 'Autenticação',
            action: $action,
            description: $description,
            model: null,
            oldValues: [],
            newValues: [],
            user: $user,
            context: $context,
        );
    }

    private function meaningfulChanges(Model $model): array
    {
        $ignore = ['updated_at', 'created_at'];
        $changes = collect($model->getChanges())
            ->except($ignore)
            ->all();

        if ($changes === []) {
            return ['old' => [], 'new' => []];
        }

        $keys = array_keys($changes);

        return [
            'old' => $this->sanitizeSnapshot($this->normalizeValue(Arr::only($model->getRawOriginal(), $keys))),
            'new' => $this->sanitizeSnapshot($this->normalizeValue(Arr::only($model->getAttributes(), $keys))),
        ];
    }

    private function modelSnapshot(Model $model): array
    {
        return $this->sanitizeSnapshot($this->normalizeValue($model->attributesToArray()));
    }

    private function describeModel(string $verb, Model $model): string
    {
        $label = class_basename($model);
        $identifier = $this->resolveModelLabel($model);

        return trim("{$label} {$verb} {$identifier}");
    }

    private function resolveModelLabel(Model $model): string
    {
        foreach (['name', 'title', 'titulo', 'nome_completo_paciente', 'subject', 'email'] as $attribute) {
            if (filled($model->getAttribute($attribute))) {
                return (string) $model->getAttribute($attribute);
            }
        }

        return '#' . (string) $model->getKey();
    }

    private function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof Model) {
            return $this->normalizeValue($value->attributesToArray());
        }

        if ($value instanceof Collection) {
            return $value->map(fn (mixed $item): mixed => $this->normalizeValue($item))->all();
        }

        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM);
        }

        if (is_array($value)) {
            $normalized = [];

            foreach ($value as $key => $item) {
                $normalized[$key] = $this->normalizeValue($item);
            }

            return $normalized;
        }

        return $value;
    }

    private function sanitizeSnapshot(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        foreach (self::SENSITIVE_KEYS as $key) {
            unset($value[$key]);
        }

        foreach ($value as $key => $item) {
            if (is_array($item)) {
                $value[$key] = $this->sanitizeSnapshot($item);
            }
        }

        return $value;
    }

    private function requestIp(?Request $request): ?string
    {
        return $request?->ip();
    }

    private function requestUrl(?Request $request): ?string
    {
        return $request?->fullUrl();
    }

    private function requestMethod(?Request $request): ?string
    {
        return $request?->method();
    }

    private function requestSessionId(?Request $request): ?string
    {
        return $request?->session()?->getId();
    }

    private function detectBrowser(?Request $request): ?string
    {
        $agent = strtolower((string) $request?->userAgent());

        return match (true) {
            str_contains($agent, 'edg/') => 'Edge',
            str_contains($agent, 'chrome/') && ! str_contains($agent, 'edg/') => 'Chrome',
            str_contains($agent, 'firefox/') => 'Firefox',
            str_contains($agent, 'safari/') && ! str_contains($agent, 'chrome/') => 'Safari',
            str_contains($agent, 'opr/') || str_contains($agent, 'opera') => 'Opera',
            default => filled($agent) ? 'Unknown' : null,
        };
    }

    private function detectPlatform(?Request $request): ?string
    {
        $agent = strtolower((string) $request?->userAgent());

        return match (true) {
            str_contains($agent, 'windows') => 'Windows',
            str_contains($agent, 'mac os') || str_contains($agent, 'macintosh') => 'macOS',
            str_contains($agent, 'android') => 'Android',
            str_contains($agent, 'iphone') || str_contains($agent, 'ipad') || str_contains($agent, 'ios') => 'iOS',
            str_contains($agent, 'linux') => 'Linux',
            default => filled($agent) ? 'Unknown' : null,
        };
    }

    private function detectDevice(?Request $request): ?string
    {
        $agent = strtolower((string) $request?->userAgent());

        return match (true) {
            str_contains($agent, 'tablet') || str_contains($agent, 'ipad') => 'Tablet',
            str_contains($agent, 'mobi') || str_contains($agent, 'android') || str_contains($agent, 'iphone') => 'Mobile',
            filled($agent) => 'Desktop',
            default => null,
        };
    }
}

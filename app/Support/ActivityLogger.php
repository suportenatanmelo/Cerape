<?php

namespace App\Support;

use App\Jobs\StoreActivityLogJob;
use App\Models\ActivityLog;
use App\Support\Concerns\HasActivityLogs;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivityLogger
{
    private const IGNORED_ATTRIBUTES = [
        'created_at',
        'updated_at',
        'remember_token',
        'session',
        'tokens',
        '_token',
        '_method',
    ];

    private const MASKED_ATTRIBUTES = [
        'password',
        'current_password',
        'password_confirmation',
    ];

    public function __construct(
        private readonly Request $request,
        private readonly BrowserDetector $browserDetector,
    ) {
    }

    public function modelCreated(Model $model, ?string $module = null, ?string $description = null): void
    {
        $this->storeForModel('create', $model, [], $this->modelValues($model), $module, $description);
    }

    public function modelUpdated(Model $model, ?string $module = null, ?string $description = null): void
    {
        $changes = $this->filteredChanges($model);

        if ($changes === []) {
            return;
        }

        $oldValues = [];

        foreach (array_keys($changes) as $attribute) {
            $oldValues[$attribute] = $this->normalizeValue($model->getOriginal($attribute));
        }

        $this->storeForModel('update', $model, $oldValues, $changes, $module, $description);
    }

    public function modelDeleted(Model $model, ?string $module = null, ?string $description = null): void
    {
        $this->storeForModel('delete', $model, $this->modelValues($model), [], $module, $description);
    }

    public function modelRestored(Model $model, ?string $module = null, ?string $description = null): void
    {
        $this->storeForModel('restore', $model, [], $this->modelValues($model), $module, $description);
    }

    public function modelForceDeleted(Model $model, ?string $module = null, ?string $description = null): void
    {
        $this->storeForModel('force_delete', $model, $this->modelValues($model), [], $module, $description);
    }

    public function login(Authenticatable $user, string $description = 'Login realizado'): void
    {
        $this->store([
            'user_id' => $this->currentUserId() ?? $this->authId($user),
            'module' => 'Autenticação',
            'action' => 'login',
            'description' => $description,
            'model_type' => $user::class,
            'model_id' => $this->authId($user),
            'old_values' => null,
            'new_values' => null,
        ]);
    }

    public function logout(?Authenticatable $user = null, string $description = 'Logout realizado'): void
    {
        $this->store([
            'user_id' => $this->currentUserId() ?? $this->authId($user),
            'module' => 'Autenticação',
            'action' => 'logout',
            'description' => $description,
            'model_type' => $user ? $user::class : null,
            'model_id' => $this->authId($user),
            'old_values' => null,
            'new_values' => null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $credentials
     */
    public function failedLogin(array $credentials, string $description = 'Login inválido'): void
    {
        $this->store([
            'user_id' => $this->currentUserId(),
            'module' => 'Autenticação',
            'action' => 'failed_login',
            'description' => $description,
            'model_type' => null,
            'model_id' => null,
            'old_values' => ['email' => $credentials['email'] ?? null],
            'new_values' => null,
        ]);
    }

    public function blockedAccess(?Authenticatable $user, string $description = 'Usuário bloqueado tentando acessar o sistema'): void
    {
        $this->store([
            'user_id' => $this->currentUserId() ?? $this->authId($user),
            'module' => 'Usuários',
            'action' => 'view',
            'description' => $description,
            'model_type' => $user ? $user::class : null,
            'model_id' => $this->authId($user),
            'old_values' => null,
            'new_values' => null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $oldValues
     * @param  array<string, mixed>  $newValues
     */
    public function custom(
        string $module,
        string $action,
        string $description,
        ?Model $model = null,
        array $oldValues = [],
        array $newValues = [],
    ): void {
        $this->store([
            'user_id' => $this->currentUserId(),
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'model_type' => $model ? $model::class : null,
            'model_id' => $model?->getKey(),
            'old_values' => $oldValues !== [] ? $oldValues : null,
            'new_values' => $newValues !== [] ? $newValues : null,
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function store(array $payload): void
    {
        if (app()->runningInConsole()) {
            return;
        }

        $payload = array_merge([
            'user_id' => null,
            'module' => 'Geral',
            'action' => 'update',
            'description' => null,
            'model_type' => null,
            'model_id' => null,
            'old_values' => null,
            'new_values' => null,
            'executed_at' => now(),
        ], $payload, $this->requestContext(), $this->browserContext());

        if ($this->shouldQueue()) {
            StoreActivityLogJob::dispatch($payload)
                ->afterCommit()
                ->afterResponse();

            return;
        }

        ActivityLog::query()->create($payload);
    }

    private function storeForModel(
        string $action,
        Model $model,
        array $oldValues,
        array $newValues,
        ?string $module = null,
        ?string $description = null,
    ): void {
        if ($model instanceof ActivityLog) {
            return;
        }

        $module = $module ?? $this->resolveModule($model);
        $description = $description ?? $this->resolveDescription($action, $model, $module);

        $this->store([
            'user_id' => $this->currentUserId(),
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'model_type' => $model::class,
            'model_id' => $model->getKey(),
            'old_values' => $oldValues !== [] ? $oldValues : null,
            'new_values' => $newValues !== [] ? $newValues : null,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function modelValues(Model $model): array
    {
        $values = [];

        foreach ($this->filteredAttributes($model) as $attribute => $value) {
            $values[$attribute] = $this->normalizeValue($value);
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    private function filteredChanges(Model $model): array
    {
        $changes = [];

        foreach ($model->getChanges() as $attribute => $value) {
            if ($this->isIgnoredAttribute($model, $attribute)) {
                continue;
            }

            $changes[$attribute] = $this->normalizeValue($this->isMaskedAttribute($attribute) ? '[hidden]' : $value);
        }

        return $changes;
    }

    /**
     * @return array<string, mixed>
     */
    private function filteredAttributes(Model $model): array
    {
        $attributes = [];

        foreach ($model->getAttributes() as $attribute => $value) {
            if ($this->isIgnoredAttribute($model, $attribute)) {
                continue;
            }

            $attributes[$attribute] = $this->isMaskedAttribute($attribute) ? '[hidden]' : $value;
        }

        return $attributes;
    }

    private function isIgnoredAttribute(Model $model, string $attribute): bool
    {
        if (in_array($attribute, array_merge(self::IGNORED_ATTRIBUTES, $this->modelIgnoredAttributes($model)), true)) {
            return true;
        }

        return Str::endsWith($attribute, ['_token', '_tokens']);
    }

    private function isMaskedAttribute(string $attribute): bool
    {
        return in_array($attribute, array_merge(self::MASKED_ATTRIBUTES, $this->modelMaskedAttributes()), true);
    }

    /**
     * @return array<int, string>
     */
    private function modelIgnoredAttributes(Model $model): array
    {
        $ignored = [];

        if (! in_array(HasActivityLogs::class, class_uses_recursive($model), true)) {
            return $ignored;
        }

        if (method_exists($model, 'activityLogIgnoredAttributes')) {
            $ignored = array_merge($ignored, (array) $model->activityLogIgnoredAttributes());
        }

        return $ignored;
    }

    /**
     * @return array<int, string>
     */
    private function modelMaskedAttributes(): array
    {
        return [];
    }

    private function resolveModule(Model $model): string
    {
        if (in_array(HasActivityLogs::class, class_uses_recursive($model), true) && method_exists($model, 'activityLogModule')) {
            $module = $model->activityLogModule();

            if (is_string($module) && filled($module)) {
                return $module;
            }
        }

        $map = config('activity-logs.modules', []);

        foreach ($map as $class => $module) {
            if ($model instanceof $class) {
                return (string) $module;
            }
        }

        return match (true) {
            str_contains($model::class, 'Role') || str_contains($model::class, 'Permission') => 'Usuários',
            str_contains($model::class, 'Acolhido') => 'Acolhidos',
            str_contains($model::class, 'Prontuario') => 'Prontuários',
            str_contains($model::class, 'Saude') => 'Saúde',
            str_contains($model::class, 'Demanda') => 'Demandas',
            str_contains($model::class, 'Financeiro') || str_contains($model::class, 'EmpresaParceira') || str_contains($model::class, 'FrenteTrabalho') => 'Financeiro',
            str_contains($model::class, 'Agenda') => 'Agenda',
            str_contains($model::class, 'Reuniao') => 'Reuniões',
            str_contains($model::class, 'GeradorAtividade') => 'Gerador de Atividades',
            str_contains($model::class, 'Atividade') => 'Atividades',
            str_contains($model::class, 'CmsContent') => 'CMS',
            str_contains($model::class, 'BlogPost') => 'Blog',
            str_contains($model::class, 'Newsletter') => 'Newsletter',
            str_contains($model::class, 'Gallery') || str_contains($model::class, 'Galeria') => 'Galeria',
            str_contains($model::class, 'HeroSlide') => 'Hero Slides',
            str_contains($model::class, 'FrontendSetting') || str_contains($model::class, 'ThemePalette') || str_contains($model::class, 'ContactLead') || str_contains($model::class, 'TeamMember') || str_contains($model::class, 'PillarCard') => 'Frontend',
            default => Str::headline(class_basename($model::class)),
        };
    }

    private function resolveDescription(string $action, Model $model, string $module): string
    {
        $label = $this->resolveRecordLabel($model, $module);

        return match ($action) {
            'create' => "Criou {$label}",
            'update' => "Alterou {$label}",
            'delete' => "Excluiu {$label}",
            'restore' => "Restaurou {$label}",
            'force_delete' => "Removeu definitivamente {$label}",
            default => Str::headline(str_replace('_', ' ', $action)) . ' em ' . $label,
        };
    }

    private function resolveRecordLabel(Model $model, string $module): string
    {
        $label = null;

        if (in_array(HasActivityLogs::class, class_uses_recursive($model), true) && method_exists($model, 'activityLogLabel')) {
            $label = $model->activityLogLabel();
        }

        $label = filled($label) ? $label : collect([
            $model->getAttribute('name'),
            $model->getAttribute('titulo'),
            $model->getAttribute('title'),
            $model->getAttribute('nome_completo_paciente'),
            $model->getAttribute('descricao'),
            $model->getAttribute('slug'),
            $model->getKey(),
        ])->first(fn (mixed $value): bool => filled($value));

        return trim($module . ' ' . (string) ($label ?: class_basename($model)));
    }

    private function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof Carbon) {
            return $value->toDateTimeString();
        }

        if ($value instanceof Model) {
            return [
                'type' => $value::class,
                'id' => $value->getKey(),
                'label' => $this->resolveRecordLabel($value, $this->resolveModule($value)),
            ];
        }

        if ($value instanceof Collection) {
            return $value->map(fn (mixed $item): mixed => $this->normalizeValue($item))->all();
        }

        if (is_array($value)) {
            return collect($value)->map(fn (mixed $item): mixed => $this->normalizeValue($item))->all();
        }

        return in_array($value, [true, false, null], true) || is_scalar($value)
            ? $value
            : (string) $value;
    }

    /**
     * @return array<string, mixed>
     */
    private function requestContext(): array
    {
        $sessionId = null;

        if ($this->request->hasSession()) {
            $sessionId = $this->request->session()->getId();
        }

        return [
            'url' => $this->request->fullUrl() ?: null,
            'method' => $this->request->method() ?: null,
            'session_id' => $sessionId,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function browserContext(): array
    {
        return $this->browserDetector->detect($this->request->userAgent());
    }

    private function currentUserId(): ?int
    {
        $id = Auth::id();

        return $id !== null ? (int) $id : null;
    }

    private function authId(?Authenticatable $user): ?int
    {
        if ($user === null) {
            return null;
        }

        $id = $user->getAuthIdentifier();

        return is_numeric($id) ? (int) $id : null;
    }

    private function shouldQueue(): bool
    {
        return (bool) config('activity-logs.queue', true) && ! app()->runningInConsole();
    }
    /**
     * @param  mixed  $value
     * @return mixed
     */
    private function sanitizePayload(mixed $value): mixed
    {
        if (is_string($value)) {
            return $this->sanitizeString($value);
        }

        if ($value instanceof Collection) {
            return $value->map(fn (mixed $item): mixed => $this->sanitizePayload($item))->all();
        }

        if (is_array($value)) {
            $sanitized = [];

            foreach ($value as $key => $item) {
                $sanitized[$key] = $this->sanitizePayload($item);
            }

            return $sanitized;
        }

        if ($value instanceof \Stringable) {
            return $this->sanitizeString((string) $value);
        }

        return $value;
    }

    private function sanitizeString(string $value): string
    {
        if ($value === '' || preg_match('//u', $value) === 1) {
            return $value;
        }

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'UTF-8//IGNORE', $value);

            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($value, 'UTF-8', 'UTF-8');

            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        return '';
    }
}
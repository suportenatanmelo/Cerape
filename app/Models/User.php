<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;
use App\Support\Concerns\HasActivityLogs;
use App\Support\UserRoleManager;
use App\Support\ImageStorageNaming;


#[Fillable([
    'name',
    'email',
    'acolhido_id',
    'password',
    'avatar',
    'cpf',
    'endereco',
    'uf',
    'nacionalidade',
    'data_nascimento',
    'active_status',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasRoles, HasActivityLogs;

    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_CADASTROS = 'cadastros';
    public const ROLE_LEITURA = 'leitura';

    public const PERMISSION_STATUS_ACTIVE = 'active';
    public const PERMISSION_DASHBOARD_VIEW = 'View:Dashboard';
    public const PERMISSION_WIDGETS_VIEW = 'View:Widgets';
    public const PERMISSION_USER_VIEW = 'View:User';

    public function activityLogModule(): ?string
    {
        return 'Usuários';
    }

    public function activityLogLabel(): ?string
    {
        return 'Usuário';
    }

/**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'data_nascimento' => 'date',
            'active_status' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function (self $user): void {
            ImageStorageNaming::syncStoredImage($user, 'avatar', 'profile-avatar', $user->name);
        });

        static::deleted(function (self $user): void {
            ImageStorageNaming::removeStoredPath($user->avatar);
        });
    }

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->isRestrictedToAcolhido()) {
            return UserRoleManager::hasAssignedRole($this);
        }

        return true;
    }

    public function avaliacoesPessoais(): HasMany
    {
        return $this->hasMany(AvaliacaoPessoal::class);
    }

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }

    public function prontuariosEvolucao(): HasMany
    {
        return $this->hasMany(ProntuarioEvolucao::class);
    }

    public function feedbackMessagesEnviadas(): HasMany
    {
        return $this->hasMany(FeedbackFamiliarMessage::class, 'sender_id');
    }

    public function isRestrictedToAcolhido(): bool
    {
        return filled($this->acolhido_id);
    }

    public function linkedAcolhidoId(): ?int
    {
        return $this->acolhido_id ? (int) $this->acolhido_id : null;
    }

    public function canAccessAcolhido(?int $acolhidoId): bool
    {
        if (! $this->isRestrictedToAcolhido()) {
            return true;
        }

        return $acolhidoId !== null && $this->linkedAcolhidoId() === $acolhidoId;
    }

    public function hasCompletedFamilyProfile(): bool
    {
        return filled($this->avatar)
            && filled($this->cpf)
            && filled($this->data_nascimento)
            && filled($this->endereco)
            && filled($this->uf)
            && filled($this->nacionalidade);
    }

    public function portalSlug(): string
    {
        $slug = Str::slug($this->name);

        return trim(($slug !== '' ? $slug : 'usuario') . '-' . $this->getKey(), '-');
    }

    public function hasAclPermission(string $permission): bool
    {
        return $this->can($permission);
    }

    public static function permissionLabel(string $permission): string
    {
        return match ($permission) {
            self::PERMISSION_DASHBOARD_VIEW => 'Visualizar dashboard',
            self::PERMISSION_WIDGETS_VIEW => 'Visualizar widgets',
            self::PERMISSION_USER_VIEW => 'Visualizar usuarios',
            default => str($permission)->replace([':', '_', '-'], ' ')->title()->toString(),
        };
    }

    public function getAccessStatusLabelAttribute(): string
    {
        return (bool) ($this->attributes['is_blocked'] ?? false) ? 'Bloqueado' : 'Liberado';
    }

    public function getPermissionStatusLabelAttribute(): string
    {
        $status = $this->attributes['permission_status'] ?? self::PERMISSION_STATUS_ACTIVE;

        return match ($status) {
            self::PERMISSION_STATUS_ACTIVE => 'Ativo',
            'inactive' => 'Inativo',
            'pending' => 'Pendente',
            'blocked' => 'Bloqueado',
            default => str((string) $status)->replace(['_', '-'], ' ')->title()->toString(),
        };
    }

    //class User extends Authenticatable implements HasAvatar

    public function getFilamentAvatarUrl(): ?string
    {
        $avatar = $this->resolveAvatarUrl($this->avatar);

        return filled($avatar) ? $avatar : null;
    }

    private function resolveAvatarUrl(?string $path): ?string
    {
        $path = $this->normalizePublicPath($path);

        if ($path === null) {
            return null;
        }

        $disk = Storage::disk('public');
        $normalizedPath = ltrim(Str::replaceFirst('/storage/', '', parse_url($path, PHP_URL_PATH) ?: $path), '/');

        $candidatePaths = array_values(array_unique(array_filter([
            $normalizedPath,
            'documentos/profile-avatar/' . basename($normalizedPath),
            'documentos/user-avatar/' . basename($normalizedPath),
            trim((string) config('chatify.user_avatar.folder'), '/') . '/' . basename($normalizedPath),
        ])));

        foreach ($candidatePaths as $candidatePath) {
            if ($disk->exists($candidatePath)) {
                return route('media.serve', ['path' => $candidatePath]);
            }
        }

        return Str::startsWith($path, ['http://', 'https://', '//', 'data:'])
            ? $path
            : null;
    }

    private function normalizePublicPath(?string $path): ?string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://', '//', 'data:'])) {
            $parsed = parse_url($path);

            if (is_array($parsed) && filled($parsed['path'] ?? null)) {
                $normalized = ltrim((string) $parsed['path'], '/');

                return Str::startsWith($normalized, 'storage/')
                    ? '/' . $normalized
                    : '/storage/' . $normalized;
            }

            return $path;
        }

        $path = ltrim(Str::replaceFirst('storage/', '', $path), '/');

        return Storage::disk('public')->url($path);
    }

}

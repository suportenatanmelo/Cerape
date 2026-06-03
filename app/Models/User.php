<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\HasAvatar;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use App\Support\PdfImage;
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
use App\Support\UserRoleManager;


#[Fillable([
    'name',
    'email',
    'acolhido_id',
    'password',
    'avatar',
    'cpf',
    'funcao_usuario',
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
    use HasFactory, Notifiable, HasRoles;


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

    public function resolveAvatarAbsolutePath(): ?string
    {
        $avatar = trim((string) $this->avatar);

        if ($avatar === '') {
            return null;
        }

        if (Str::startsWith($avatar, ['http://', 'https://', '//', 'data:'])) {
            return null;
        }

        foreach ([
            Storage::disk('public'),
            Storage::disk('local'),
        ] as $disk) {
            foreach ([
                $avatar,
                'users/avatars/' . basename($avatar),
                'avatars/' . basename($avatar),
                'private/avatars/' . basename($avatar),
                'private/' . basename($avatar),
            ] as $candidate) {
                if ($disk->exists($candidate)) {
                    $path = $disk->path($candidate);

                    if (is_file($path) && is_readable($path)) {
                        return $path;
                    }
                }
            }
        }

        return null;
    }

    public function getFilamentAvatarUrl(): ?string
    {
        if (blank($this->avatar)) {
            return null;
        }

        return PdfImage::publicUrl($this->avatar)
            ?? url('/user/avatar');
    }
}

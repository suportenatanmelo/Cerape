<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


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
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements HasAvatar
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
        ];
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

    //class User extends Authenticatable implements HasAvatar

    public function getFilamentAvatarUrl(): ?string
    {
        return asset('storage/' . $this->avatar);
    }
}

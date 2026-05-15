<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


#[Fillable([
    'name',
    'email',
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

    public function prontuariosEvolucao(): HasMany
    {
        return $this->hasMany(ProntuarioEvolucao::class);
    }

    //class User extends Authenticatable implements HasAvatar

    public function getFilamentAvatarUrl(): ?string
    {
        return asset('storage/' . $this->avatar);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FrenteTrabalho extends Model
{
    protected $table = 'frentes_trabalho';

    protected $fillable = ['nome', 'descricao', 'ativo'];

    protected $casts = ['ativo' => 'boolean'];

    public function diarias(): HasMany
    {
        return $this->hasMany(DiariaTrabalho::class, 'frente_trabalho_id');
    }
}

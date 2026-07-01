<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmpresaParceira extends Model
{
    protected $table = 'empresas_parceiras';

    protected $fillable = [
        'nome',
        'cnpj',
        'telefone',
        'responsavel',
        'endereco',
        'ativo',
        'observacoes',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function diarias(): HasMany
    {
        return $this->hasMany(DiariaTrabalho::class, 'empresa_parceira_id');
    }
}

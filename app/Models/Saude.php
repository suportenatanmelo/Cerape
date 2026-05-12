<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saude extends Model
{
    protected $fillable = [
        'acolhido_id',
        'condicoes_saude',
        'faz_tratamento_medico',
        'medicamentos_em_uso',
        'alergias_restricoes',
        'observacoes_clinicas',
    ];

    protected $casts = [
        'condicoes_saude' => 'array',
        'faz_tratamento_medico' => 'boolean',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }
}

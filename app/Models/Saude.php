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
        'usa_medicacao_psicoativa',
        'nome_medicacao_psicoativa',
        'dosagem_medicacao_psicoativa',
        'prescrito_profissional',
        'diagnosticado',
    ];

    protected $casts = [
        'condicoes_saude' => 'array',
        'faz_tratamento_medico' => 'boolean',
        'usa_medicacao_psicoativa' => 'boolean',
        'nome_medicacao_psicoativa' => 'array',
        'prescrito_profissional' => 'boolean',
        'diagnosticado' => 'array',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }
}

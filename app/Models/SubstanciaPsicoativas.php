<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class SubstanciaPsicoativas extends Model
{
    protected $fillable = [
        'acolhido_id',
        'nome',
        'frequencia',
        'quantidade',
        'via_administracao',
        'tempo_uso',
        'ultima_vez',
        'observacoes',
        'houve_dependentes_quimicos_familia_convivencia',
        'nome_pessoa_dependente_familiar',
        'influencia_terceiro_inicio_uso',
        'tipo_relacao_influencia_terceiro',
        'participou_grupos_apoio',
        'qual_grupo_apoio',
        'teve_internacoes_anteriores',
        'quantas_internacoes_anteriores',
        'onde_internacoes_anteriores',
        'quando_internacoes_anteriores',
        'lembra_tempo_acolhimento_anterior',
        'tempo_acolhimento_anterior',
        'esteve_unidade_prisional_ou_similar',
        'periodo_unidade_prisional',
        'motivo_unidade_prisional',
        'processos_judiciais_andamento',
        'motivo_processos_judiciais_andamento',
        'processos_judiciais_anteriores',
        'motivo_processos_judiciais_anteriores',
        'impactos_trabalho_uso_substancias',
        'detalhes_impactos_trabalho_uso_substancias',
        'desempregado_por_uso_substancias',
        'tempo_desemprego_por_uso_substancias',
        'impacto_convivio_familiar_uso_substancias',
        'detalhes_impacto_convivio_familiar',
        'frequencia_impacto_convivio_familiar',
        'internacoes_hospitalares_uso_substancias',
        'quantidade_internacoes_hospitalares_uso_substancias',
        'detalhes_internacoes_hospitalares_uso_substancias',
    ];

    protected $casts = [
        'houve_dependentes_quimicos_familia_convivencia' => 'boolean',
        'participou_grupos_apoio' => 'boolean',
        'teve_internacoes_anteriores' => 'boolean',
        'lembra_tempo_acolhimento_anterior' => 'boolean',
        'processos_judiciais_andamento' => 'boolean',
        'processos_judiciais_anteriores' => 'boolean',
        'desempregado_por_uso_substancias' => 'boolean',
        'impacto_convivio_familiar_uso_substancias' => 'boolean',
        'internacoes_hospitalares_uso_substancias' => 'boolean',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }
}

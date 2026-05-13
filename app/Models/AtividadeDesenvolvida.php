<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtividadeDesenvolvida extends Model
{
    protected $table = 'atividades_desenvolvidas';

    protected $fillable = [
        'acolhido_id',
        'atendimento_grupo_12_passos',
        'horario_atendimento_grupo_12_passos',
        'atendimentos_grupos',
        'horario_atendimentos_grupos',
        'atendimentos_individuais_conselheiros',
        'horario_atendimentos_individuais_conselheiros',
        'conhecimento_dependencia_spa',
        'horario_conhecimento_dependencia_spa',
        'atendimento_familia',
        'detalhes_atendimento_familia',
        'visitacao_familiares_responsaveis',
        'dia_visitacao_familiares_responsaveis',
        'atividades_esportivas',
        'salao_jogos',
        'atividades_ludicas_culturais_musicais',
        'biblioteca_clube_leitura',
        'atividades_espiritualidade',
        'atividade_auto_cuidado_sociabilidade',
        'detalhes_auto_cuidado_sociabilidade',
        'atividades_aprendizagem',
        'detalhes_atividades_praticas_inclusivas',
        'planejamento_saida',
        'planejamento_saida_observacoes',
        'eixos_planejamento_saida',
        'detalhes_eixos_planejamento_saida',
        'saida_comunidade',
        'saida_comunidade_outros',
        'observacoes_gerais',
    ];

    protected $casts = [
        'atendimento_grupo_12_passos' => 'boolean',
        'atendimentos_grupos' => 'boolean',
        'atendimentos_individuais_conselheiros' => 'boolean',
        'conhecimento_dependencia_spa' => 'boolean',
        'atendimento_familia' => 'boolean',
        'visitacao_familiares_responsaveis' => 'boolean',
        'atividades_esportivas' => 'array',
        'salao_jogos' => 'array',
        'atividades_ludicas_culturais_musicais' => 'array',
        'atividades_espiritualidade' => 'array',
        'atividade_auto_cuidado_sociabilidade' => 'boolean',
        'atividades_aprendizagem' => 'array',
        'planejamento_saida' => 'array',
        'eixos_planejamento_saida' => 'array',
        'saida_comunidade' => 'array',
    ];

    public function acolhido(): BelongsTo
    {
        return $this->belongsTo(Acolhido::class);
    }
}

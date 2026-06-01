<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atividades_desenvolvidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acolhido_id')->nullable()->constrained()->nullOnDelete();

            $table->boolean('atendimento_grupo_12_passos')->nullable();
            $table->string('horario_atendimento_grupo_12_passos')->nullable();
            $table->boolean('atendimentos_grupos')->nullable();
            $table->string('horario_atendimentos_grupos')->nullable();
            $table->boolean('atendimentos_individuais_conselheiros')->nullable();
            $table->string('horario_atendimentos_individuais_conselheiros')->nullable();
            $table->boolean('conhecimento_dependencia_spa')->nullable();
            $table->string('horario_conhecimento_dependencia_spa')->nullable();
            $table->boolean('atendimento_familia')->nullable();
            $table->string('detalhes_atendimento_familia')->nullable();
            $table->boolean('visitacao_familiares_responsaveis')->nullable();
            $table->string('dia_visitacao_familiares_responsaveis')->nullable();

            $table->json('atividades_esportivas')->nullable();
            $table->json('salao_jogos')->nullable();
            $table->json('atividades_ludicas_culturais_musicais')->nullable();
            $table->text('biblioteca_clube_leitura')->nullable();
            $table->json('atividades_espiritualidade')->nullable();
            $table->boolean('atividade_auto_cuidado_sociabilidade')->nullable();
            $table->text('detalhes_auto_cuidado_sociabilidade')->nullable();
            $table->json('atividades_aprendizagem')->nullable();
            $table->text('detalhes_atividades_praticas_inclusivas')->nullable();

            $table->json('planejamento_saida')->nullable();
            $table->text('planejamento_saida_observacoes')->nullable();
            $table->json('eixos_planejamento_saida')->nullable();
            $table->text('detalhes_eixos_planejamento_saida')->nullable();

            $table->json('saida_comunidade')->nullable();
            $table->text('saida_comunidade_outros')->nullable();
            $table->text('observacoes_gerais')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atividades_desenvolvidas');
    }
};

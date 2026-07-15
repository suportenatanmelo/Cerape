<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('substancia_psicoativas', function (Blueprint $table): void {
            $table->boolean('participou_grupos_apoio')
                ->default(false)
                ->after('tipo_relacao_influencia_terceiro');
            $table->string('qual_grupo_apoio')
                ->nullable()
                ->after('participou_grupos_apoio');

            $table->boolean('teve_internacoes_anteriores')
                ->default(false)
                ->after('qual_grupo_apoio');
            $table->string('quantas_internacoes_anteriores')
                ->nullable()
                ->after('teve_internacoes_anteriores');
            $table->string('onde_internacoes_anteriores')
                ->nullable()
                ->after('quantas_internacoes_anteriores');
            $table->string('quando_internacoes_anteriores')
                ->nullable()
                ->after('onde_internacoes_anteriores');

            $table->boolean('lembra_tempo_acolhimento_anterior')
                ->default(false)
                ->after('quando_internacoes_anteriores');
            $table->string('tempo_acolhimento_anterior')
                ->nullable()
                ->after('lembra_tempo_acolhimento_anterior');

            $table->string('esteve_unidade_prisional_ou_similar')
                ->nullable()
                ->after('tempo_acolhimento_anterior');
            $table->string('periodo_unidade_prisional')
                ->nullable()
                ->after('esteve_unidade_prisional_ou_similar');
            $table->text('motivo_unidade_prisional')
                ->nullable()
                ->after('periodo_unidade_prisional');

            $table->boolean('processos_judiciais_andamento')
                ->default(false)
                ->after('motivo_unidade_prisional');
            $table->text('motivo_processos_judiciais_andamento')
                ->nullable()
                ->after('processos_judiciais_andamento');

            $table->boolean('processos_judiciais_anteriores')
                ->default(false)
                ->after('motivo_processos_judiciais_andamento');
            $table->text('motivo_processos_judiciais_anteriores')
                ->nullable()
                ->after('processos_judiciais_anteriores');

            $table->string('impactos_trabalho_uso_substancias')
                ->nullable()
                ->after('motivo_processos_judiciais_anteriores');
            $table->text('detalhes_impactos_trabalho_uso_substancias')
                ->nullable()
                ->after('impactos_trabalho_uso_substancias');

            $table->boolean('desempregado_por_uso_substancias')
                ->default(false)
                ->after('detalhes_impactos_trabalho_uso_substancias');
            $table->string('tempo_desemprego_por_uso_substancias')
                ->nullable()
                ->after('desempregado_por_uso_substancias');

            $table->boolean('impacto_convivio_familiar_uso_substancias')
                ->default(false)
                ->after('tempo_desemprego_por_uso_substancias');
            $table->text('detalhes_impacto_convivio_familiar')
                ->nullable()
                ->after('impacto_convivio_familiar_uso_substancias');
            $table->string('frequencia_impacto_convivio_familiar')
                ->nullable()
                ->after('detalhes_impacto_convivio_familiar');

            $table->boolean('internacoes_hospitalares_uso_substancias')
                ->default(false)
                ->after('frequencia_impacto_convivio_familiar');
            $table->string('quantidade_internacoes_hospitalares_uso_substancias')
                ->nullable()
                ->after('internacoes_hospitalares_uso_substancias');
            $table->text('detalhes_internacoes_hospitalares_uso_substancias')
                ->nullable()
                ->after('quantidade_internacoes_hospitalares_uso_substancias');
        });
    }

    public function down(): void
    {
        Schema::table('substancia_psicoativas', function (Blueprint $table): void {
            $table->dropColumn([
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
            ]);
        });
    }
};

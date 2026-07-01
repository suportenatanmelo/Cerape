<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('substancia_psicoativas', function (Blueprint $table): void {
            $table->boolean('houve_dependentes_quimicos_familia_convivencia')
                ->default(false)
                ->after('observacoes');
            $table->string('nome_pessoa_dependente_familiar')
                ->nullable()
                ->after('houve_dependentes_quimicos_familia_convivencia');
            $table->string('influencia_terceiro_inicio_uso')
                ->nullable()
                ->after('nome_pessoa_dependente_familiar');
            $table->string('tipo_relacao_influencia_terceiro')
                ->nullable()
                ->after('influencia_terceiro_inicio_uso');
        });
    }

    public function down(): void
    {
        Schema::table('substancia_psicoativas', function (Blueprint $table): void {
            $table->dropColumn([
                'houve_dependentes_quimicos_familia_convivencia',
                'nome_pessoa_dependente_familiar',
                'influencia_terceiro_inicio_uso',
                'tipo_relacao_influencia_terceiro',
            ]);
        });
    }
};

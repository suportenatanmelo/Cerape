<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saudes', function (Blueprint $table) {
            // Campos principais do modelo de saude relacionados ao acolhido, condicoes de saude, tratamento e observacoes clinicas.
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->json('condicoes_saude')->nullable();
            $table->boolean('faz_tratamento_medico')->default(false);
            $table->text('medicamentos_em_uso')->nullable();
            $table->text('alergias_restricoes')->nullable();
            $table->text('observacoes_clinicas')->nullable();
            // Uso da medicação psicoativa
            $table->boolean('usa_medicacao_psicoativa')->default(false);
            $table->json('nome_medicacao_psicoativa')->nullable();
            $table->text('dosagem_medicacao_psicoativa')->nullable();
            $table->boolean('prescrito_profissional')->default(false);
            // Campos adicionais para monitoramento de saude mental e uso de substancias
            $table->json('diagnosticado')->nullable();



            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saudes');
    }
};

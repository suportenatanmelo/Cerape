<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->json('condicoes_saude')->nullable();
            $table->boolean('faz_tratamento_medico')->default(false);
            $table->text('medicamentos_em_uso')->nullable();
            $table->text('alergias_restricoes')->nullable();
            $table->text('observacoes_clinicas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saudes');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('demandas_acolhidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->string('demanda');
            $table->text('observacoes')->nullable();
            $table->dateTime('saida_prevista_em');
            $table->dateTime('retorno_previsto_em');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demandas_acolhidos');
    }
};

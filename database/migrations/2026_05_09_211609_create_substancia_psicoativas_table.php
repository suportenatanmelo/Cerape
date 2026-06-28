<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('substancia_psicoativas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->string('nome');
            $table->string('frequencia')->nullable();
            $table->string('quantidade')->nullable();
            $table->string('via_administracao')->nullable();
            $table->string('tempo_uso')->nullable();
            $table->string('ultima_vez')->nullable();
            $table->string('observacoes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('substancia_psicoativas');
    }
};

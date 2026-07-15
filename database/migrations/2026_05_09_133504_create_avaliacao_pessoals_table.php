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
        Schema::create('avaliacao_pessoals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->string('dias_na_casa');
            $table->decimal('controler');
            $table->decimal('autonomia');
            $table->decimal('transparencia');
            $table->decimal('superacao');
            $table->decimal('autocuidado');
            $table->decimal('Total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avaliacao_pessoals');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prontuarios_evolucao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->dateTime('data_prontuario');
            $table->longText('conteudo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prontuarios_evolucao');
    }
};

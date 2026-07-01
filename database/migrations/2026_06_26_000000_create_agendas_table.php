<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendas', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('acolhido_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('funcionario_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->string('tipo')->default('Consulta');
            $table->string('status')->default('Agendado');
            $table->string('cor')->default('#3b82f6');
            $table->boolean('dia_todo')->default(false);
            $table->boolean('notificar')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};

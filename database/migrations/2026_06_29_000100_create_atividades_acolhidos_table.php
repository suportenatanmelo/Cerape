<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('atividades_acolhidos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('gerador_atividade_id')->constrained('geradores_atividades')->cascadeOnDelete();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->string('atividade');
            $table->text('demanda')->nullable();
            $table->date('data_programacao');
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status')->default('pendente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('atividades_acolhidos');
    }
};

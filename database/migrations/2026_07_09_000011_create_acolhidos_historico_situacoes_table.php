<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acolhidos_historico_situacoes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('acolhido_id')->constrained('acolhidos')->cascadeOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('situacao_anterior')->nullable();
            $table->string('situacao_nova')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acolhidos_historico_situacoes');
    }
};

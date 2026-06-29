<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saques_financeiros', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carteira_acolhido_id')->constrained('carteiras_acolhidos')->cascadeOnDelete();
            $table->date('data');
            $table->decimal('valor', 12, 2);
            $table->string('responsavel');
            $table->string('assinatura')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('transferencias_familia', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carteira_acolhido_id')->constrained('carteiras_acolhidos')->cascadeOnDelete();
            $table->string('nome_pessoa');
            $table->string('parentesco')->nullable();
            $table->string('pix')->nullable();
            $table->string('banco')->nullable();
            $table->date('data');
            $table->decimal('valor', 12, 2);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('compras_internas', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carteira_acolhido_id')->constrained('carteiras_acolhidos')->cascadeOnDelete();
            $table->string('categoria');
            $table->date('data');
            $table->decimal('valor', 12, 2);
            $table->string('responsavel')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras_internas');
        Schema::dropIfExists('transferencias_familia');
        Schema::dropIfExists('saques_financeiros');
    }
};

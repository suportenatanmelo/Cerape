<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas_parceiras', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->string('cnpj', 20)->nullable();
            $table->string('telefone', 30)->nullable();
            $table->string('responsavel')->nullable();
            $table->string('endereco')->nullable();
            $table->boolean('ativo')->default(true);
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('frentes_trabalho', function (Blueprint $table): void {
            $table->id();
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('carteiras_acolhidos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->decimal('saldo_atual', 12, 2)->default(0);
            $table->decimal('total_recebido', 12, 2)->default(0);
            $table->decimal('total_sacado', 12, 2)->default(0);
            $table->decimal('total_retido_instituicao', 12, 2)->default(0);
            $table->timestamps();

            $table->unique('acolhido_id');
        });

        Schema::create('diarias_trabalho', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('empresa_parceira_id')->constrained('empresas_parceiras')->cascadeOnDelete();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('frente_trabalho_id')->nullable()->constrained('frentes_trabalho')->nullOnDelete();
            $table->date('data');
            $table->string('tipo_servico');
            $table->unsignedInteger('quantidade_dias')->default(1);
            $table->decimal('valor_diaria', 12, 2);
            $table->decimal('valor_total', 12, 2);
            $table->decimal('valor_cerape', 12, 2)->default(0);
            $table->decimal('valor_acolhido', 12, 2)->default(0);
            $table->enum('situacao', ['pago', 'pendente', 'cancelado'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('movimentacoes_financeiras', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('acolhido_id')->constrained()->cascadeOnDelete();
            $table->foreignId('diaria_trabalho_id')->nullable()->constrained('diarias_trabalho')->nullOnDelete();
            $table->string('tipo');
            $table->decimal('valor', 12, 2);
            $table->decimal('saldo_anterior', 12, 2)->default(0);
            $table->decimal('saldo_posterior', 12, 2)->default(0);
            $table->string('descricao');
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_financeiras');
        Schema::dropIfExists('diarias_trabalho');
        Schema::dropIfExists('carteiras_acolhidos');
        Schema::dropIfExists('frentes_trabalho');
        Schema::dropIfExists('empresas_parceiras');
    }
};

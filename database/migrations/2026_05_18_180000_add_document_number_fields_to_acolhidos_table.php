<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            $table->string('numero_rg')->nullable()->after('documentos_outros');
            $table->string('numero_cpf')->nullable()->after('numero_rg');
            $table->string('numero_certidao_nascimento')->nullable()->after('numero_cpf');
            $table->string('numero_certidao_casamento')->nullable()->after('numero_certidao_nascimento');
            $table->string('numero_carteira_trabalho')->nullable()->after('numero_certidao_casamento');
            $table->string('numero_titulo_eleitor')->nullable()->after('numero_carteira_trabalho');
            $table->string('numero_nis')->nullable()->after('numero_titulo_eleitor');
            $table->string('numero_cartao_sus')->nullable()->after('numero_nis');
        });
    }

    public function down(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            $table->dropColumn([
                'numero_rg',
                'numero_cpf',
                'numero_certidao_nascimento',
                'numero_certidao_casamento',
                'numero_carteira_trabalho',
                'numero_titulo_eleitor',
                'numero_nis',
                'numero_cartao_sus',
            ]);
        });
    }
};

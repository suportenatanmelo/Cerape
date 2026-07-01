<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            $table->string('interventor_nome_completo')->nullable()->after('responsavel_pela_intervencao_do_acolhido');
            $table->string('interventor_cpf', 14)->nullable()->after('interventor_nome_completo');
            $table->string('interventor_rg')->nullable()->after('interventor_cpf');
            $table->string('interventor_exp')->nullable()->after('interventor_rg');
            $table->string('interventor_rg_uf', 2)->nullable()->after('interventor_exp');
            $table->string('interventor_profissao')->nullable()->after('interventor_rg_uf');
            $table->date('interventor_data_nascimento')->nullable()->after('interventor_profissao');
            $table->string('interventor_residente')->nullable()->after('interventor_data_nascimento');
            $table->string('interventor_complemento')->nullable()->after('interventor_residente');
            $table->string('interventor_bairro')->nullable()->after('interventor_complemento');
            $table->string('interventor_cidade')->nullable()->after('interventor_bairro');
            $table->string('interventor_endereco_uf', 2)->nullable()->after('interventor_cidade');
            $table->string('interventor_telefone_contato', 15)->nullable()->after('interventor_endereco_uf');
        });
    }

    public function down(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            $table->dropColumn([
                'interventor_nome_completo',
                'interventor_cpf',
                'interventor_rg',
                'interventor_exp',
                'interventor_rg_uf',
                'interventor_profissao',
                'interventor_data_nascimento',
                'interventor_residente',
                'interventor_complemento',
                'interventor_bairro',
                'interventor_cidade',
                'interventor_endereco_uf',
                'interventor_telefone_contato',
            ]);
        });
    }
};

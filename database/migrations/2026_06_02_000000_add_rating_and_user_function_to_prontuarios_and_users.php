<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            if (! Schema::hasColumn('users', 'funcao_usuario')) {
                $table->string('funcao_usuario')->nullable()->after('cpf');
            }
        });

        Schema::table('prontuarios_evolucao', function (Blueprint $table): void {
            if (Schema::hasColumn('prontuarios_evolucao', 'acolhido_id')) {
                $table->unsignedBigInteger('acolhido_id')->nullable()->change();
            }

            if (Schema::hasColumn('prontuarios_evolucao', 'data_prontuario')) {
                $table->dateTime('data_prontuario')->nullable()->change();
            }

            if (Schema::hasColumn('prontuarios_evolucao', 'conteudo')) {
                $table->longText('conteudo')->nullable()->change();
            }

            if (! Schema::hasColumn('prontuarios_evolucao', 'funcao_responsavel_informacao')) {
                $table->string('funcao_responsavel_informacao')->nullable()->after('user_id');
            }

            if (! Schema::hasColumn('prontuarios_evolucao', 'nota_elogio')) {
                $table->unsignedTinyInteger('nota_elogio')->nullable()->after('proxima_data_prontuario');
            }
        });
    }

    public function down(): void
    {
        Schema::table('prontuarios_evolucao', function (Blueprint $table): void {
            if (Schema::hasColumn('prontuarios_evolucao', 'nota_elogio')) {
                $table->dropColumn('nota_elogio');
            }

            if (Schema::hasColumn('prontuarios_evolucao', 'funcao_responsavel_informacao')) {
                $table->dropColumn('funcao_responsavel_informacao');
            }

            if (Schema::hasColumn('prontuarios_evolucao', 'conteudo')) {
                $table->longText('conteudo')->nullable(false)->change();
            }

            if (Schema::hasColumn('prontuarios_evolucao', 'data_prontuario')) {
                $table->dateTime('data_prontuario')->nullable(false)->change();
            }

            if (Schema::hasColumn('prontuarios_evolucao', 'acolhido_id')) {
                $table->unsignedBigInteger('acolhido_id')->nullable(false)->change();
            }
        });

        Schema::table('users', function (Blueprint $table): void {
            if (Schema::hasColumn('users', 'funcao_usuario')) {
                $table->dropColumn('funcao_usuario');
            }
        });
    }
};

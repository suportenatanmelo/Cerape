<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('geradores_atividades', function (Blueprint $table): void {
            if (! Schema::hasColumn('geradores_atividades', 'status')) {
                $table->string('status')->default('pendente')->after('observacoes');
            }

            if (! Schema::hasColumn('geradores_atividades', 'acolhido_id')) {
                $table->foreignId('acolhido_id')->nullable()->constrained()->cascadeOnDelete()->after('status');
            }

            if (! Schema::hasColumn('geradores_atividades', 'profissional_id')) {
                $table->foreignId('profissional_id')->nullable()->constrained('users')->nullOnDelete()->after('acolhido_id');
            }

            if (! Schema::hasColumn('geradores_atividades', 'data_atividade')) {
                $table->date('data_atividade')->nullable()->after('profissional_id');
            }
        });

        Schema::table('prontuarios_evolucao', function (Blueprint $table): void {
            if (! Schema::hasColumn('prontuarios_evolucao', 'atividade_gerada_id')) {
                $table->foreignId('atividade_gerada_id')->nullable()->constrained('atividades_acolhidos')->nullOnDelete()->after('acolhido_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('prontuarios_evolucao', function (Blueprint $table): void {
            if (Schema::hasColumn('prontuarios_evolucao', 'atividade_gerada_id')) {
                $table->dropConstrainedForeignId('atividade_gerada_id');
            }
        });

        Schema::table('geradores_atividades', function (Blueprint $table): void {
            if (Schema::hasColumn('geradores_atividades', 'data_atividade')) {
                $table->dropColumn('data_atividade');
            }

            if (Schema::hasColumn('geradores_atividades', 'profissional_id')) {
                $table->dropConstrainedForeignId('profissional_id');
            }

            if (Schema::hasColumn('geradores_atividades', 'acolhido_id')) {
                $table->dropConstrainedForeignId('acolhido_id');
            }

            if (Schema::hasColumn('geradores_atividades', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};

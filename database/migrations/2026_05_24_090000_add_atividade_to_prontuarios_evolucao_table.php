<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prontuarios_evolucao', function (Blueprint $table): void {
            if (! Schema::hasColumn('prontuarios_evolucao', 'atividade')) {
                $table->string('atividade')->nullable()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('prontuarios_evolucao', function (Blueprint $table): void {
            if (Schema::hasColumn('prontuarios_evolucao', 'atividade')) {
                $table->dropColumn('atividade');
            }
        });
    }
};

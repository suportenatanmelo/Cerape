<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prontuarios_evolucao', function (Blueprint $table): void {
            if (! Schema::hasColumn('prontuarios_evolucao', 'proxima_data_prontuario')) {
                $table->dateTime('proxima_data_prontuario')->nullable()->after('data_prontuario');
            }
        });
    }

    public function down(): void
    {
        Schema::table('prontuarios_evolucao', function (Blueprint $table): void {
            if (Schema::hasColumn('prontuarios_evolucao', 'proxima_data_prontuario')) {
                $table->dropColumn('proxima_data_prontuario');
            }
        });
    }
};

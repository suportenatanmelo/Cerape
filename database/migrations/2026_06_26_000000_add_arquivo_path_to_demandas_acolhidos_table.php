<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandas_acolhidos', function (Blueprint $table): void {
            if (! Schema::hasColumn('demandas_acolhidos', 'arquivo_path')) {
                $table->string('arquivo_path')->nullable()->after('demanda');
            }
        });
    }

    public function down(): void
    {
        Schema::table('demandas_acolhidos', function (Blueprint $table): void {
            if (Schema::hasColumn('demandas_acolhidos', 'arquivo_path')) {
                $table->dropColumn('arquivo_path');
            }
        });
    }
};

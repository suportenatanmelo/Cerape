<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('acolhido_galerias')
            ->whereNull('imagens')
            ->update(['imagens' => json_encode([])]);

        Schema::table('acolhido_galerias', function (Blueprint $table): void {
            $table->json('imagens')->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('acolhido_galerias')
            ->whereNull('imagens')
            ->update(['imagens' => json_encode([])]);

        Schema::table('acolhido_galerias', function (Blueprint $table): void {
            $table->json('imagens')->nullable(false)->change();
        });
    }
};

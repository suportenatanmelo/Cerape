<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('acolhidos')
            ->whereNull('responsavel_pela_intervencao_do_acolhido')
            ->update([
                'responsavel_pela_intervencao_do_acolhido' => DB::raw("NULLIF(interventor_nome_completo, '')"),
            ]);

        Schema::table('acolhidos', function (Blueprint $table): void {
            $table->string('responsavel_pela_intervencao_do_acolhido')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            $table->string('responsavel_pela_intervencao_do_acolhido')->nullable(false)->change();
        });
    }
};

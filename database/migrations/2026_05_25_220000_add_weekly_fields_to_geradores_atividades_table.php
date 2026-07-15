<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('geradores_atividades', function (Blueprint $table): void {
            $table->date('periodo_fim')->nullable()->after('data_programacao');
            $table->json('atividades_planejadas')->nullable()->after('acolhidos_ids');
        });

        $periodEndExpression = DB::connection()->getDriverName() === 'sqlite'
            ? "date(data_programacao, '+6 days')"
            : 'DATE_ADD(data_programacao, INTERVAL 6 DAY)';

        DB::table('geradores_atividades')
            ->whereNull('periodo_fim')
            ->update([
                'periodo_fim' => DB::raw($periodEndExpression),
            ]);
    }

    public function down(): void
    {
        Schema::table('geradores_atividades', function (Blueprint $table): void {
            $table->dropColumn([
                'periodo_fim',
                'atividades_planejadas',
            ]);
        });
    }
};

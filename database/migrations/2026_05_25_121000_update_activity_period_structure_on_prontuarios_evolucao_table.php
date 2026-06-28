<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prontuarios_evolucao', function (Blueprint $table): void {
            if (Schema::hasColumn('prontuarios_evolucao', 'atividade')) {
                $table->text('atividade')->nullable()->change();
            }

            if (Schema::hasColumn('prontuarios_evolucao', 'periodo_atividade')) {
                $table->text('periodo_atividade')->nullable()->change();
            } else {
                $table->text('periodo_atividade')->nullable()->after('atividade');
            }
        });

        DB::table('prontuarios_evolucao')
            ->whereNotNull('periodo_atividade')
            ->orderBy('id')
            ->lazy()
            ->each(function (object $record): void {
                $period = trim((string) $record->periodo_atividade);

                if ($period === '') {
                    return;
                }

                $activities = array_values(array_filter(explode(',', (string) $record->atividade)));
                $payload = [];

                foreach ($activities as $activity) {
                    $payload[$activity] = $period;
                }

                DB::table('prontuarios_evolucao')
                    ->where('id', $record->id)
                    ->update([
                        'periodo_atividade' => $payload === [] ? null : json_encode($payload, JSON_UNESCAPED_UNICODE),
                    ]);
            });
    }

    public function down(): void
    {
        DB::table('prontuarios_evolucao')
            ->whereNotNull('periodo_atividade')
            ->orderBy('id')
            ->lazy()
            ->each(function (object $record): void {
                $payload = json_decode((string) $record->periodo_atividade, true);

                if (! is_array($payload) || $payload === []) {
                    return;
                }

                $firstPeriod = reset($payload);

                DB::table('prontuarios_evolucao')
                    ->where('id', $record->id)
                    ->update([
                        'periodo_atividade' => is_string($firstPeriod) && $firstPeriod !== '' ? $firstPeriod : null,
                    ]);
            });

        Schema::table('prontuarios_evolucao', function (Blueprint $table): void {
            if (Schema::hasColumn('prontuarios_evolucao', 'periodo_atividade')) {
                $table->string('periodo_atividade')->nullable()->change();
            }

            if (Schema::hasColumn('prontuarios_evolucao', 'atividade')) {
                $table->string('atividade')->nullable()->change();
            }
        });
    }
};

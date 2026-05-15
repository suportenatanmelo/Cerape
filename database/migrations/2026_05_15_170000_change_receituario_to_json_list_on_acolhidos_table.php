<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acolhidos', function (Blueprint $table) {
            $table->text('receituario')->nullable()->default(null)->change();
        });

        DB::table('acolhidos')
            ->select(['id', 'receituario'])
            ->orderBy('id')
            ->each(function (object $acolhido): void {
                $value = $acolhido->receituario;

                if ($value === null || $value === '') {
                    return;
                }

                $decoded = json_decode($value, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return;
                }

                DB::table('acolhidos')
                    ->where('id', $acolhido->id)
                    ->update([
                        'receituario' => json_encode([$value], JSON_UNESCAPED_UNICODE),
                    ]);
            });
    }

    public function down(): void
    {
        DB::table('acolhidos')
            ->select(['id', 'receituario'])
            ->orderBy('id')
            ->each(function (object $acolhido): void {
                $value = $acolhido->receituario;

                if ($value === null || $value === '') {
                    return;
                }

                $decoded = json_decode($value, true);

                if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
                    return;
                }

                DB::table('acolhidos')
                    ->where('id', $acolhido->id)
                    ->update([
                        'receituario' => $decoded[0] ?? null,
                    ]);
            });

        Schema::table('acolhidos', function (Blueprint $table) {
            $table->string('receituario')->nullable()->default(null)->change();
        });
    }
};

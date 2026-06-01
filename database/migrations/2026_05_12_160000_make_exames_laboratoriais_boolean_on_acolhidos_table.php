<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('acolhidos') || ! Schema::hasColumn('acolhidos', 'exames_laboratoriais')) {
            return;
        }

        Schema::table('acolhidos', function (Blueprint $table) {
            $table->boolean('exames_laboratoriais_boolean')->default(false);
        });

        DB::table('acolhidos')
            ->select(['id', 'exames_laboratoriais'])
            ->orderBy('id')
            ->get()
            ->each(function (object $acolhido): void {
                DB::table('acolhidos')
                    ->where('id', $acolhido->id)
                    ->update([
                        'exames_laboratoriais_boolean' => $this->normalizeLegacyValue($acolhido->exames_laboratoriais),
                    ]);
            });

        Schema::table('acolhidos', function (Blueprint $table) {
            $table->dropColumn('exames_laboratoriais');
        });

        Schema::table('acolhidos', function (Blueprint $table) {
            $table->boolean('exames_laboratoriais')->default(false);
        });

        DB::table('acolhidos')
            ->update([
                'exames_laboratoriais' => DB::raw('exames_laboratoriais_boolean'),
            ]);

        Schema::table('acolhidos', function (Blueprint $table) {
            $table->dropColumn('exames_laboratoriais_boolean');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('acolhidos') || ! Schema::hasColumn('acolhidos', 'exames_laboratoriais')) {
            return;
        }

        Schema::table('acolhidos', function (Blueprint $table) {
            $table->boolean('exames_laboratoriais_boolean')->default(false);
        });

        DB::table('acolhidos')
            ->update([
                'exames_laboratoriais_boolean' => DB::raw('exames_laboratoriais'),
            ]);

        Schema::table('acolhidos', function (Blueprint $table) {
            $table->dropColumn('exames_laboratoriais');
        });

        Schema::table('acolhidos', function (Blueprint $table) {
            $table->json('exames_laboratoriais')->nullable();
        });

        DB::table('acolhidos')
            ->select(['id', 'exames_laboratoriais_boolean'])
            ->orderBy('id')
            ->get()
            ->each(function (object $acolhido): void {
                DB::table('acolhidos')
                    ->where('id', $acolhido->id)
                    ->update([
                        'exames_laboratoriais' => $acolhido->exames_laboratoriais_boolean ? json_encode(['Informado']) : null,
                    ]);
            });

        Schema::table('acolhidos', function (Blueprint $table) {
            $table->dropColumn('exames_laboratoriais_boolean');
        });
    }

    private function normalizeLegacyValue(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (bool) $value;
        }

        if (is_array($value)) {
            return count(array_filter($value, fn(mixed $item): bool => $this->hasMeaningfulValue($item))) > 0;
        }

        if ($value === null) {
            return false;
        }

        $stringValue = trim((string) $value);

        if ($stringValue === '') {
            return false;
        }

        $normalized = mb_strtolower($stringValue);

        if (in_array($normalized, ['0', 'false', 'nao', 'null', '[]', '{}'], true)) {
            return false;
        }

        $decoded = json_decode($stringValue, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            if (is_bool($decoded)) {
                return $decoded;
            }

            if (is_array($decoded)) {
                return count(array_filter($decoded, fn(mixed $item): bool => $this->hasMeaningfulValue($item))) > 0;
            }

            if (is_int($decoded) || is_float($decoded)) {
                return (bool) $decoded;
            }

            if (is_string($decoded)) {
                return trim($decoded) !== '';
            }
        }

        return true;
    }

    private function hasMeaningfulValue(mixed $value): bool
    {
        if ($value === null) {
            return false;
        }

        if (is_bool($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (bool) $value;
        }

        if (is_array($value)) {
            return count(array_filter($value, fn(mixed $item): bool => $this->hasMeaningfulValue($item))) > 0;
        }

        return trim((string) $value) !== '';
    }
};

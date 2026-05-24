<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            if (! Schema::hasColumn('acolhidos', 'escolaridade_observacao')) {
                $table->string('escolaridade_observacao')->nullable()->after('escolaridade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            if (Schema::hasColumn('acolhidos', 'escolaridade_observacao')) {
                $table->dropColumn('escolaridade_observacao');
            }
        });
    }
};

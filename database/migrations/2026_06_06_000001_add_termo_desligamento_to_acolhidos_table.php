<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            if (! Schema::hasColumn('acolhidos', 'termo_desligamento')) {
                $table->boolean('termo_desligamento')->default(false)->after('formado');
            }
        });
    }

    public function down(): void
    {
        Schema::table('acolhidos', function (Blueprint $table): void {
            if (Schema::hasColumn('acolhidos', 'termo_desligamento')) {
                $table->dropColumn('termo_desligamento');
            }
        });
    }
};

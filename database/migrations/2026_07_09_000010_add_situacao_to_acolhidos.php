<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('acolhidos')) {
            return;
        }

        Schema::table('acolhidos', function (Blueprint $table): void {
            if (! Schema::hasColumn('acolhidos', 'situacao')) {
                $table->string('situacao')->default('acolhido')->after('ativo');
                $table->index('situacao');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('acolhidos')) {
            return;
        }

        Schema::table('acolhidos', function (Blueprint $table): void {
            if (Schema::hasColumn('acolhidos', 'situacao')) {
                $table->dropIndex(['situacao']);
                $table->dropColumn('situacao');
            }
        });
    }
};

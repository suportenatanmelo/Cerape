<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('theme_palettes')) {
            return;
        }

        if (! Schema::hasColumn('theme_palettes', 'slug')) {
            Schema::table('theme_palettes', function (Blueprint $table): void {
                $table->string('slug')->nullable()->after('name');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('theme_palettes')) {
            return;
        }

        if (Schema::hasColumn('theme_palettes', 'slug')) {
            Schema::table('theme_palettes', function (Blueprint $table): void {
                $table->dropColumn('slug');
            });
        }
    }
};

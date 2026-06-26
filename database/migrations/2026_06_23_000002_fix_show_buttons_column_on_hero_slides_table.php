<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hero_slides')) {
            return;
        }

        Schema::table('hero_slides', function (Blueprint $table): void {
            if (! Schema::hasColumn('hero_slides', 'show_buttons')) {
                $table->boolean('show_buttons')->default(true)->after('cta_url');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('hero_slides')) {
            return;
        }

        Schema::table('hero_slides', function (Blueprint $table): void {
            if (Schema::hasColumn('hero_slides', 'show_buttons')) {
                $table->dropColumn('show_buttons');
            }
        });
    }
};

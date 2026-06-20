<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_slides', function (Blueprint $table): void {
            $table->boolean('show_buttons')->default(true)->after('cta_url');
        });
    }

    public function down(): void
    {
        Schema::table('hero_slides', function (Blueprint $table): void {
            $table->dropColumn('show_buttons');
        });
    }
};

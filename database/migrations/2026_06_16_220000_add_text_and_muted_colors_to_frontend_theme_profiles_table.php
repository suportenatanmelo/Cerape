<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_theme_profiles', function (Blueprint $table): void {
            $table->string('text_color', 32)->nullable()->after('ink_color');
            $table->string('muted_color', 32)->nullable()->after('text_color');
        });
    }

    public function down(): void
    {
        Schema::table('frontend_theme_profiles', function (Blueprint $table): void {
            $table->dropColumn([
                'text_color',
                'muted_color',
            ]);
        });
    }
};

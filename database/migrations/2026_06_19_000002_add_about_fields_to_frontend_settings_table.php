<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            $table->string('about_title')->nullable()->after('hero_subtitle');
            $table->text('about_paragraph_one')->nullable()->after('about_title');
            $table->text('about_paragraph_two')->nullable()->after('about_paragraph_one');
            $table->string('about_image_path')->nullable()->after('about_paragraph_two');
            $table->string('menu_label_about')->nullable()->after('menu_label_home');
        });
    }

    public function down(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'about_title',
                'about_paragraph_one',
                'about_paragraph_two',
                'about_image_path',
                'menu_label_about',
            ]);
        });
    }
};

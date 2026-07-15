<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = [
            'blog_posts',
            'carousel_slides',
            'contact_messages',
            'contact_pages',
            'frontend_footer_settings',
            'frontend_site_events',
            'frontend_testimonials',
            'frontend_theme_profiles',
            'homes',
        ];

        foreach ($tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            Schema::drop($table);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('homes', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });

        Schema::create('blog_posts', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });

        Schema::create('carousel_slides', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });

        Schema::create('contact_pages', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });

        Schema::create('contact_messages', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });

        Schema::create('frontend_footer_settings', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });

        Schema::create('frontend_site_events', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });

        Schema::create('frontend_testimonials', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });

        Schema::create('frontend_theme_profiles', function (Blueprint $table): void {
            $table->id();
            $table->timestamps();
        });
    }
};

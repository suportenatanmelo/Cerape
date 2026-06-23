<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('frontend_settings')) {
            return;
        }

        Schema::table('frontend_settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('frontend_settings', 'about_title')) {
                $table->string('about_title')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'about_paragraph_one')) {
                $table->text('about_paragraph_one')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'about_paragraph_two')) {
                $table->text('about_paragraph_two')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'about_image_path')) {
                $table->string('about_image_path')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'menu_label_about')) {
                $table->string('menu_label_about')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('frontend_settings')) {
            return;
        }

        Schema::table('frontend_settings', function (Blueprint $table): void {
            $columns = array_values(array_filter([
                Schema::hasColumn('frontend_settings', 'about_title') ? 'about_title' : null,
                Schema::hasColumn('frontend_settings', 'about_paragraph_one') ? 'about_paragraph_one' : null,
                Schema::hasColumn('frontend_settings', 'about_paragraph_two') ? 'about_paragraph_two' : null,
                Schema::hasColumn('frontend_settings', 'about_image_path') ? 'about_image_path' : null,
                Schema::hasColumn('frontend_settings', 'menu_label_about') ? 'menu_label_about' : null,
            ]));

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

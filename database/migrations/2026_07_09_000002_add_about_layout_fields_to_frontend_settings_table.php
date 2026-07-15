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
            if (! Schema::hasColumn('frontend_settings', 'about_text_alignment')) {
                $table->string('about_text_alignment')->nullable()->default('left')->after('about_video_height');
            }
            if (! Schema::hasColumn('frontend_settings', 'about_image_position')) {
                $table->string('about_image_position')->nullable()->default('right')->after('about_text_alignment');
            }
            if (! Schema::hasColumn('frontend_settings', 'about_show_image')) {
                $table->boolean('about_show_image')->default(true)->after('about_image_position');
            }
            if (! Schema::hasColumn('frontend_settings', 'about_show_video')) {
                $table->boolean('about_show_video')->default(true)->after('about_show_image');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('frontend_settings')) {
            return;
        }

        if (Schema::hasColumn('frontend_settings', 'about_text_alignment')) {
            Schema::table('frontend_settings', function (Blueprint $table): void {
                $table->dropColumn(['about_text_alignment', 'about_image_position', 'about_show_image', 'about_show_video']);
            });
        }
    }
};

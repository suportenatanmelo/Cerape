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
            if (! Schema::hasColumn('frontend_settings', 'about_video_url')) {
                $table->string('about_video_url')->nullable()->after('about_image_path');
            }

            if (! Schema::hasColumn('frontend_settings', 'about_video_width')) {
                $table->unsignedInteger('about_video_width')->nullable()->after('about_video_url');
            }

            if (! Schema::hasColumn('frontend_settings', 'about_video_height')) {
                $table->unsignedInteger('about_video_height')->nullable()->after('about_video_width');
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
                Schema::hasColumn('frontend_settings', 'about_video_url') ? 'about_video_url' : null,
                Schema::hasColumn('frontend_settings', 'about_video_width') ? 'about_video_width' : null,
                Schema::hasColumn('frontend_settings', 'about_video_height') ? 'about_video_height' : null,
            ]));

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

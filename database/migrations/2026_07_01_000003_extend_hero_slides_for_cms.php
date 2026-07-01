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
            if (! Schema::hasColumn('hero_slides', 'mobile_image_path')) {
                $table->string('mobile_image_path')->nullable()->after('image_path');
            }

            if (! Schema::hasColumn('hero_slides', 'secondary_cta_label')) {
                $table->string('secondary_cta_label')->nullable()->after('cta_url');
            }

            if (! Schema::hasColumn('hero_slides', 'secondary_cta_url')) {
                $table->string('secondary_cta_url')->nullable()->after('secondary_cta_label');
            }

            if (! Schema::hasColumn('hero_slides', 'text_color')) {
                $table->string('text_color')->default('#ffffff')->after('secondary_cta_url');
            }

            if (! Schema::hasColumn('hero_slides', 'alignment')) {
                $table->string('alignment', 20)->default('left')->after('text_color');
            }

            if (! Schema::hasColumn('hero_slides', 'overlay_color')) {
                $table->string('overlay_color')->default('#000000')->after('alignment');
            }

            if (! Schema::hasColumn('hero_slides', 'overlay_opacity')) {
                $table->unsignedTinyInteger('overlay_opacity')->default(45)->after('overlay_color');
            }

            if (! Schema::hasColumn('hero_slides', 'starts_at')) {
                $table->timestamp('starts_at')->nullable()->after('is_active');
            }

            if (! Schema::hasColumn('hero_slides', 'ends_at')) {
                $table->timestamp('ends_at')->nullable()->after('starts_at');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('hero_slides')) {
            return;
        }

        Schema::table('hero_slides', function (Blueprint $table): void {
            foreach ([
                'mobile_image_path',
                'secondary_cta_label',
                'secondary_cta_url',
                'text_color',
                'alignment',
                'overlay_color',
                'overlay_opacity',
                'starts_at',
                'ends_at',
            ] as $column) {
                if (Schema::hasColumn('hero_slides', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

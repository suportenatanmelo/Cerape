<?php

namespace App\Support;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

final class FrontendSchema
{
    public static function ensureCarouselSlidesTableExists(): void
    {
        if (Schema::hasTable('carousel_slides')) {
            return;
        }

        Schema::create('carousel_slides', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('eyebrow')->nullable();
            $table->string('eyebrow_color', 32)->nullable();
            $table->string('title');
            $table->string('title_color', 32)->nullable();
            $table->longText('description')->nullable();
            $table->string('description_color', 32)->nullable();
            $table->string('image', 512)->nullable();
            $table->string('image_alt')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_text_color', 32)->nullable();
            $table->string('cta_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public static function ensureContactPagesTableExists(): void
    {
        if (Schema::hasTable('contact_pages')) {
            self::ensureContactPagesColumns();
            return;
        }

        Schema::create('contact_pages', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->longText('intro')->nullable();
            $table->string('hero_image', 512)->nullable();
            $table->string('hero_image_alt')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('address')->nullable();
            $table->string('opening_hours')->nullable();
            $table->string('map_embed_url')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->json('social_links')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public static function ensureContactPagesColumns(): void
    {
        if (! Schema::hasTable('contact_pages')) {
            return;
        }

        Schema::table('contact_pages', function (Blueprint $table): void {
            if (! Schema::hasColumn('contact_pages', 'map_embed_code')) {
                $table->longText('map_embed_code')->nullable()->after('opening_hours');
            }
        });
    }

    public static function ensureFooterSettingsTableExists(): void
    {
        if (Schema::hasTable('frontend_footer_settings')) {
            self::ensureFooterSettingsColumns();
            return;
        }

        Schema::create('frontend_footer_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('brand_name')->nullable();
            $table->longText('tagline')->nullable();
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->longText('map_embed_code')->nullable();
            $table->string('map_embed_url')->nullable();
            $table->json('quick_links')->nullable();
            $table->json('social_links')->nullable();
            $table->string('copyright_text')->nullable();
            $table->boolean('use_theme_colors')->default(true);
            $table->string('background_color', 32)->nullable();
            $table->string('text_color', 32)->nullable();
            $table->string('muted_color', 32)->nullable();
            $table->string('border_color', 32)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public static function ensureFooterSettingsColumns(): void
    {
        if (! Schema::hasTable('frontend_footer_settings')) {
            return;
        }

        Schema::table('frontend_footer_settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('frontend_footer_settings', 'map_embed_code')) {
                $table->longText('map_embed_code')->nullable()->after('whatsapp');
            }
        });
    }
}

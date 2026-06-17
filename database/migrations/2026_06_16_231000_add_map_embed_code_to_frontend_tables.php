<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_footer_settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('frontend_footer_settings', 'map_embed_code')) {
                $table->longText('map_embed_code')->nullable()->after('whatsapp');
            }
        });

        Schema::table('contact_pages', function (Blueprint $table): void {
            if (! Schema::hasColumn('contact_pages', 'map_embed_code')) {
                $table->longText('map_embed_code')->nullable()->after('opening_hours');
            }
        });
    }

    public function down(): void
    {
        Schema::table('frontend_footer_settings', function (Blueprint $table): void {
            if (Schema::hasColumn('frontend_footer_settings', 'map_embed_code')) {
                $table->dropColumn('map_embed_code');
            }
        });

        Schema::table('contact_pages', function (Blueprint $table): void {
            if (Schema::hasColumn('contact_pages', 'map_embed_code')) {
                $table->dropColumn('map_embed_code');
            }
        });
    }
};

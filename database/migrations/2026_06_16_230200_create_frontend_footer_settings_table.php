<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('frontend_footer_settings')) {
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

    public function down(): void
    {
        Schema::dropIfExists('frontend_footer_settings');
    }
};

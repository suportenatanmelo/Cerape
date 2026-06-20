<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frontend_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('site_enabled')->default(true);
            $table->boolean('home_enabled')->default(true);
            $table->string('brand_name')->default('CERAPE');
            $table->string('hero_title')->default('Como funciona');
            $table->string('hero_subtitle')->nullable();
            $table->string('menu_label_home')->default('Iniciar');
            $table->string('menu_label_pillars')->default('Pilares');
            $table->string('menu_label_team')->default('Equipe');
            $table->string('menu_label_gallery')->default('Galeria');
            $table->string('menu_label_blog')->default('Blog');
            $table->string('menu_label_contact')->default('Contato');
            $table->string('header_primary_color')->default('#0f172a');
            $table->string('header_secondary_color')->default('#155e75');
            $table->string('footer_primary_color')->default('#111827');
            $table->string('footer_secondary_color')->default('#0f766e');
            $table->string('font_color')->default('#e5e7eb');
            $table->string('accent_color')->default('#38bdf8');
            $table->string('whatsapp_number')->nullable();
            $table->string('whatsapp_message')->nullable();
            $table->string('site_email')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_settings');
    }
};

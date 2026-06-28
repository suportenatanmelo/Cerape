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
            $table->string('logo_path')->nullable();
            $table->string('hero_title')->default('Como funciona');
            $table->string('hero_subtitle')->nullable();
            $table->string('about_title')->nullable();
            $table->text('about_paragraph_one')->nullable();
            $table->text('about_paragraph_two')->nullable();
            $table->string('about_image_path')->nullable();
            $table->string('menu_label_home')->default('Iniciar');
            $table->string('menu_label_about')->nullable();
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
            $table->string('clinic_name')->nullable();
            $table->string('clinic_address')->nullable();
            $table->string('clinic_city')->nullable();
            $table->string('clinic_state')->nullable();
            $table->string('clinic_zip_code')->nullable();
            $table->text('clinic_google_maps_embed')->nullable();
            $table->string('clinic_maps_link')->nullable();
            $table->text('clinic_description')->nullable();
            $table->string('clinic_contact_title')->default('Informações');
            $table->string('clinic_contact_name')->default('Clínica CERAPE');
            $table->string('clinic_contact_address_label')->default('Endereço');
            $table->text('clinic_contact_address_line')->nullable();
            $table->string('clinic_contact_city_label')->default('Cidade');
            $table->string('clinic_contact_city_line')->default('São Paulo');
            $table->string('clinic_contact_state_label')->default('Estado');
            $table->string('clinic_contact_state_line')->default('SP');
            $table->string('clinic_contact_zip_label')->default('CEP');
            $table->string('clinic_contact_zip_line')->default('00000-000');
            $table->string('clinic_contact_phone_label')->default('Telefone');
            $table->string('clinic_contact_phone_line')->default('(11) 0000-0000');
            $table->string('clinic_contact_email_label')->default('E-mail');
            $table->string('clinic_contact_email_line')->default('contato@cerape.com');
            $table->string('contact_eyebrow')->default('Atendimento confidencial');
            $table->string('contact_title')->default('Vamos conversar');
            $table->text('contact_description')->nullable();
            $table->string('contact_phone_label')->default('Telefone');
            $table->string('contact_phone_line')->default('(11) 0000-0000 · WhatsApp 24h');
            $table->string('contact_whatsapp_cta_label')->default('Conversar no WhatsApp');
            $table->string('contact_address_label')->default('Endereço');
            $table->text('contact_address_line')->nullable();
            $table->string('contact_email_label')->default('E-mail');
            $table->string('contact_email_line')->default('contato@cerape.com');
            $table->string('contact_form_name_placeholder')->default('Seu nome');
            $table->string('contact_form_phone_placeholder')->default('(00) 00000-0000');
            $table->string('contact_form_email_placeholder')->default('seu@email.com');
            $table->string('contact_form_message_placeholder')->default('Como podemos ajudar?');
            $table->string('whatsapp_number')->nullable();
            $table->string('whatsapp_message')->nullable();
            $table->string('site_email')->nullable();
            $table->string('site_status_password_hash')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_settings');
    }
};

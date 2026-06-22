<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
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
        });
    }

    public function down(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'contact_eyebrow',
                'contact_title',
                'contact_description',
                'contact_phone_label',
                'contact_phone_line',
                'contact_whatsapp_cta_label',
                'contact_address_label',
                'contact_address_line',
                'contact_email_label',
                'contact_email_line',
                'contact_form_name_placeholder',
                'contact_form_phone_placeholder',
                'contact_form_email_placeholder',
                'contact_form_message_placeholder',
            ]);
        });
    }
};

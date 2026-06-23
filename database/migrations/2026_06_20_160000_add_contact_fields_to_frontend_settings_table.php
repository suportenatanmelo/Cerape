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
            if (! Schema::hasColumn('frontend_settings', 'contact_eyebrow')) {
                $table->string('contact_eyebrow')->default('Atendimento confidencial');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_title')) {
                $table->string('contact_title')->default('Vamos conversar');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_description')) {
                $table->text('contact_description')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_phone_label')) {
                $table->string('contact_phone_label')->default('Telefone');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_phone_line')) {
                $table->string('contact_phone_line')->default('(11) 0000-0000 · WhatsApp 24h');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_whatsapp_cta_label')) {
                $table->string('contact_whatsapp_cta_label')->default('Conversar no WhatsApp');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_address_label')) {
                $table->string('contact_address_label')->default('Endereço');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_address_line')) {
                $table->text('contact_address_line')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_email_label')) {
                $table->string('contact_email_label')->default('E-mail');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_email_line')) {
                $table->string('contact_email_line')->default('contato@cerape.com');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_form_name_placeholder')) {
                $table->string('contact_form_name_placeholder')->default('Seu nome');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_form_phone_placeholder')) {
                $table->string('contact_form_phone_placeholder')->default('(00) 00000-0000');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_form_email_placeholder')) {
                $table->string('contact_form_email_placeholder')->default('seu@email.com');
            }

            if (! Schema::hasColumn('frontend_settings', 'contact_form_message_placeholder')) {
                $table->string('contact_form_message_placeholder')->default('Como podemos ajudar?');
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
                Schema::hasColumn('frontend_settings', 'contact_eyebrow') ? 'contact_eyebrow' : null,
                Schema::hasColumn('frontend_settings', 'contact_title') ? 'contact_title' : null,
                Schema::hasColumn('frontend_settings', 'contact_description') ? 'contact_description' : null,
                Schema::hasColumn('frontend_settings', 'contact_phone_label') ? 'contact_phone_label' : null,
                Schema::hasColumn('frontend_settings', 'contact_phone_line') ? 'contact_phone_line' : null,
                Schema::hasColumn('frontend_settings', 'contact_whatsapp_cta_label') ? 'contact_whatsapp_cta_label' : null,
                Schema::hasColumn('frontend_settings', 'contact_address_label') ? 'contact_address_label' : null,
                Schema::hasColumn('frontend_settings', 'contact_address_line') ? 'contact_address_line' : null,
                Schema::hasColumn('frontend_settings', 'contact_email_label') ? 'contact_email_label' : null,
                Schema::hasColumn('frontend_settings', 'contact_email_line') ? 'contact_email_line' : null,
                Schema::hasColumn('frontend_settings', 'contact_form_name_placeholder') ? 'contact_form_name_placeholder' : null,
                Schema::hasColumn('frontend_settings', 'contact_form_phone_placeholder') ? 'contact_form_phone_placeholder' : null,
                Schema::hasColumn('frontend_settings', 'contact_form_email_placeholder') ? 'contact_form_email_placeholder' : null,
                Schema::hasColumn('frontend_settings', 'contact_form_message_placeholder') ? 'contact_form_message_placeholder' : null,
            ]));

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

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
            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_title')) {
                $table->string('clinic_contact_title')->default('Informações');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_name')) {
                $table->string('clinic_contact_name')->default('Clínica CERAPE');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_address_label')) {
                $table->string('clinic_contact_address_label')->default('Endereço');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_address_line')) {
                $table->text('clinic_contact_address_line')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_city_label')) {
                $table->string('clinic_contact_city_label')->default('Cidade');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_city_line')) {
                $table->string('clinic_contact_city_line')->default('São Paulo');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_state_label')) {
                $table->string('clinic_contact_state_label')->default('Estado');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_state_line')) {
                $table->string('clinic_contact_state_line')->default('SP');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_zip_label')) {
                $table->string('clinic_contact_zip_label')->default('CEP');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_zip_line')) {
                $table->string('clinic_contact_zip_line')->default('00000-000');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_phone_label')) {
                $table->string('clinic_contact_phone_label')->default('Telefone');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_phone_line')) {
                $table->string('clinic_contact_phone_line')->default('(11) 0000-0000');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_email_label')) {
                $table->string('clinic_contact_email_label')->default('E-mail');
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_contact_email_line')) {
                $table->string('clinic_contact_email_line')->default('contato@cerape.com');
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
                Schema::hasColumn('frontend_settings', 'clinic_contact_title') ? 'clinic_contact_title' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_name') ? 'clinic_contact_name' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_address_label') ? 'clinic_contact_address_label' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_address_line') ? 'clinic_contact_address_line' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_city_label') ? 'clinic_contact_city_label' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_city_line') ? 'clinic_contact_city_line' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_state_label') ? 'clinic_contact_state_label' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_state_line') ? 'clinic_contact_state_line' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_zip_label') ? 'clinic_contact_zip_label' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_zip_line') ? 'clinic_contact_zip_line' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_phone_label') ? 'clinic_contact_phone_label' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_phone_line') ? 'clinic_contact_phone_line' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_email_label') ? 'clinic_contact_email_label' : null,
                Schema::hasColumn('frontend_settings', 'clinic_contact_email_line') ? 'clinic_contact_email_line' : null,
            ]));

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

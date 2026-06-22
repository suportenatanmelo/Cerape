<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
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
        });
    }

    public function down(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'clinic_contact_title',
                'clinic_contact_name',
                'clinic_contact_address_label',
                'clinic_contact_address_line',
                'clinic_contact_city_label',
                'clinic_contact_city_line',
                'clinic_contact_state_label',
                'clinic_contact_state_line',
                'clinic_contact_zip_label',
                'clinic_contact_zip_line',
                'clinic_contact_phone_label',
                'clinic_contact_phone_line',
                'clinic_contact_email_label',
                'clinic_contact_email_line',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            $table->string('clinic_name')->nullable()->after('accent_color');
            $table->string('clinic_address')->nullable()->after('clinic_name');
            $table->string('clinic_city')->nullable()->after('clinic_address');
            $table->string('clinic_state')->nullable()->after('clinic_city');
            $table->string('clinic_zip_code')->nullable()->after('clinic_state');
            $table->text('clinic_google_maps_embed')->nullable()->after('clinic_zip_code');
            $table->string('clinic_maps_link')->nullable()->after('clinic_google_maps_embed');
            $table->text('clinic_description')->nullable()->after('clinic_maps_link');
        });
    }

    public function down(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'clinic_name',
                'clinic_address',
                'clinic_city',
                'clinic_state',
                'clinic_zip_code',
                'clinic_google_maps_embed',
                'clinic_maps_link',
                'clinic_description',
            ]);
        });
    }
};

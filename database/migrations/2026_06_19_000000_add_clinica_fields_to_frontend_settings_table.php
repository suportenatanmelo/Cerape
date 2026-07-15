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
            if (! Schema::hasColumn('frontend_settings', 'clinic_name')) {
                $table->string('clinic_name')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_address')) {
                $table->string('clinic_address')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_city')) {
                $table->string('clinic_city')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_state')) {
                $table->string('clinic_state')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_zip_code')) {
                $table->string('clinic_zip_code')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_google_maps_embed')) {
                $table->text('clinic_google_maps_embed')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_maps_link')) {
                $table->string('clinic_maps_link')->nullable();
            }

            if (! Schema::hasColumn('frontend_settings', 'clinic_description')) {
                $table->text('clinic_description')->nullable();
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
                Schema::hasColumn('frontend_settings', 'clinic_name') ? 'clinic_name' : null,
                Schema::hasColumn('frontend_settings', 'clinic_address') ? 'clinic_address' : null,
                Schema::hasColumn('frontend_settings', 'clinic_city') ? 'clinic_city' : null,
                Schema::hasColumn('frontend_settings', 'clinic_state') ? 'clinic_state' : null,
                Schema::hasColumn('frontend_settings', 'clinic_zip_code') ? 'clinic_zip_code' : null,
                Schema::hasColumn('frontend_settings', 'clinic_google_maps_embed') ? 'clinic_google_maps_embed' : null,
                Schema::hasColumn('frontend_settings', 'clinic_maps_link') ? 'clinic_maps_link' : null,
                Schema::hasColumn('frontend_settings', 'clinic_description') ? 'clinic_description' : null,
            ]));

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

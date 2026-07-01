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
            if (! Schema::hasColumn('frontend_settings', 'logo_path')) {
                $table->string('logo_path')->nullable()->after('brand_name');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('frontend_settings')) {
            return;
        }

        Schema::table('frontend_settings', function (Blueprint $table): void {
            if (Schema::hasColumn('frontend_settings', 'logo_path')) {
                $table->dropColumn('logo_path');
            }
        });
    }
};

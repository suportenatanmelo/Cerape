<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('frontend_settings')) {
            return;
        }

        Schema::table('frontend_settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('frontend_settings', 'site_status_password_hash')) {
                $table->string('site_status_password_hash')->nullable();
            }
        });

        $defaultHash = hash('sha256', 'suportenatanmelo@gmail.com');

        if (Schema::hasColumn('frontend_settings', 'site_status_password_hash')) {
            DB::table('frontend_settings')
                ->whereNull('site_status_password_hash')
                ->update(['site_status_password_hash' => $defaultHash]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('frontend_settings')) {
            return;
        }

        Schema::table('frontend_settings', function (Blueprint $table): void {
            if (Schema::hasColumn('frontend_settings', 'site_status_password_hash')) {
                $table->dropColumn('site_status_password_hash');
            }
        });
    }
};

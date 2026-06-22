<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            $table->string('site_status_password_hash')->nullable()->after('site_enabled');
        });

        $defaultHash = hash('sha256', 'suportenatanmelo@gmail.com');

        DB::table('frontend_settings')
            ->whereNull('site_status_password_hash')
            ->update(['site_status_password_hash' => $defaultHash]);
    }

    public function down(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            $table->dropColumn('site_status_password_hash');
        });
    }
};

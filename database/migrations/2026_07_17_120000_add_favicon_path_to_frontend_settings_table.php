<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            if (! Schema::hasColumn('frontend_settings', 'favicon_path')) {
                $table->string('favicon_path')->nullable()->after('logo_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('frontend_settings', function (Blueprint $table): void {
            if (Schema::hasColumn('frontend_settings', 'favicon_path')) {
                $table->dropColumn('favicon_path');
            }
        });
    }
};

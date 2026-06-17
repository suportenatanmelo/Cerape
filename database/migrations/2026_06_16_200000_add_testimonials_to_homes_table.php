<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table): void {
            $table->json('testimonials')->nullable()->after('signup_image_alt');
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table): void {
            $table->dropColumn('testimonials');
        });
    }
};

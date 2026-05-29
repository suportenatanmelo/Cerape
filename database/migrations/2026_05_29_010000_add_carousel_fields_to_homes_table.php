<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('homes', function (Blueprint $table): void {
            $table->boolean('enable_carousel')->default(false)->after('signup_image_alt');
            $table->json('carousel_items')->nullable()->after('enable_carousel');
        });
    }

    public function down(): void
    {
        Schema::table('homes', function (Blueprint $table): void {
            $table->dropColumn([
                'enable_carousel',
                'carousel_items',
            ]);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carousel_slides', function (Blueprint $table): void {
            $table->string('eyebrow_color', 32)->nullable()->after('eyebrow');
            $table->string('title_color', 32)->nullable()->after('title');
            $table->string('description_color', 32)->nullable()->after('description');
            $table->string('cta_text_color', 32)->nullable()->after('cta_label');
        });
    }

    public function down(): void
    {
        Schema::table('carousel_slides', function (Blueprint $table): void {
            $table->dropColumn([
                'eyebrow_color',
                'title_color',
                'description_color',
                'cta_text_color',
            ]);
        });
    }
};

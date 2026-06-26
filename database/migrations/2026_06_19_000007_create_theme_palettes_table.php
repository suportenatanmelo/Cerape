<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theme_palettes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('primary_color');
            $table->string('secondary_color');
            $table->string('surface_color');
            $table->string('background_color');
            $table->string('text_color');
            $table->string('accent_color');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('position')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theme_palettes');
    }
};

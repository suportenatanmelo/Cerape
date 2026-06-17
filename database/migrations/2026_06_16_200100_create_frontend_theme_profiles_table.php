<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('frontend_theme_profiles', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('preset_key')->nullable()->index();
            $table->string('primary_color', 32)->nullable();
            $table->string('secondary_color', 32)->nullable();
            $table->string('accent_color', 32)->nullable();
            $table->string('background_color', 32)->nullable();
            $table->string('surface_color', 32)->nullable();
            $table->string('surface_strong_color', 32)->nullable();
            $table->string('ink_color', 32)->nullable();
            $table->string('body_font')->nullable();
            $table->string('display_font')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frontend_theme_profiles');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carousel_slides', function (Blueprint $table): void {
            $table->id();
            $table->string('slug')->unique();
            $table->string('eyebrow')->nullable();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('image', 512)->nullable();
            $table->string('image_alt')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carousel_slides');
    }
};

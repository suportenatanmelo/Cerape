<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('theme_palettes');
    }

    public function down(): void
    {
        if (! Schema::hasTable('theme_palettes')) {
            Schema::create('theme_palettes', function ($table): void {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable();
                $table->string('primary_color')->default('#0f172a');
                $table->string('secondary_color')->default('#155e75');
                $table->string('surface_color')->default('#ffffff');
                $table->string('background_color')->default('#f8fafc');
                $table->string('text_color')->default('#0f172a');
                $table->string('accent_color')->default('#38bdf8');
                $table->string('success_color')->default('#16a34a');
                $table->string('warning_color')->default('#f59e0b');
                $table->string('danger_color')->default('#dc2626');
                $table->string('info_color')->default('#0284c7');
                $table->string('header_color')->default('#0f172a');
                $table->string('footer_color')->default('#111827');
                $table->string('button_color')->default('#0f766e');
                $table->string('link_color')->default('#0e7490');
                $table->string('card_color')->default('#ffffff');
                $table->string('border_color')->default('#e5e7eb');
                $table->string('hover_color')->default('#0f766e');
                $table->string('focus_color')->default('#38bdf8');
                $table->string('dark_background_color')->default('#020617');
                $table->string('dark_surface_color')->default('#0f172a');
                $table->boolean('is_active')->default(false);
                $table->boolean('is_current')->default(false);
                $table->unsignedInteger('position')->default(1);
                $table->timestamps();
            });
        }
    }
};

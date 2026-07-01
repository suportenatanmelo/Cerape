<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('theme_palettes')) {
            return;
        }

        Schema::table('theme_palettes', function (Blueprint $table): void {
            foreach ([
                'success_color' => '#16a34a',
                'warning_color' => '#f59e0b',
                'danger_color' => '#dc2626',
                'info_color' => '#0284c7',
                'header_color' => '#0f172a',
                'footer_color' => '#111827',
                'button_color' => '#0f766e',
                'link_color' => '#0e7490',
                'card_color' => '#ffffff',
                'border_color' => '#e5e7eb',
                'hover_color' => '#0f766e',
                'focus_color' => '#38bdf8',
                'dark_background_color' => '#020617',
                'dark_surface_color' => '#0f172a',
            ] as $column => $default) {
                if (! Schema::hasColumn('theme_palettes', $column)) {
                    $table->string($column)->default($default);
                }
            }

            if (! Schema::hasColumn('theme_palettes', 'is_current')) {
                $table->boolean('is_current')->default(false)->index();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('theme_palettes')) {
            return;
        }

        Schema::table('theme_palettes', function (Blueprint $table): void {
            foreach ([
                'success_color',
                'warning_color',
                'danger_color',
                'info_color',
                'header_color',
                'footer_color',
                'button_color',
                'link_color',
                'card_color',
                'border_color',
                'hover_color',
                'focus_color',
                'dark_background_color',
                'dark_surface_color',
                'is_current',
            ] as $column) {
                if (Schema::hasColumn('theme_palettes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

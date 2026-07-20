<?php

namespace Database\Seeders;

use App\Models\ThemePalette;
use Illuminate\Database\Seeder;

class ThemePaletteSeeder extends Seeder
{
    public function run(): void
    {
        $palettes = [
            ['Azul Profissional', 'azul-profissional', '#2563eb', '#1d4ed8', '#ffffff', '#eff6ff', '#0f172a', '#38bdf8'],
            ['Verde Esmeralda', 'verde-esmeralda', '#10b981', '#047857', '#ffffff', '#ecfdf5', '#022c22', '#6ee7b7'],
            ['Roxo Moderno', 'roxo-moderno', '#8b5cf6', '#6d28d9', '#ffffff', '#faf5ff', '#1f2937', '#d8b4fe'],
            ['Laranja Vibrante', 'laranja-vibrante', '#f97316', '#c2410c', '#ffffff', '#fff7ed', '#1f2937', '#fdba74'],
            ['Vermelho Elegante', 'vermelho-elegante', '#dc2626', '#991b1b', '#ffffff', '#fef2f2', '#1f2937', '#fca5a5'],
            ['Azul Oceano', 'azul-oceano', '#0ea5e9', '#0369a1', '#ffffff', '#f0f9ff', '#0f172a', '#7dd3fc'],
            ['Verde Natureza', 'verde-natureza', '#84cc16', '#166534', '#ffffff', '#f7fee7', '#052e16', '#bef264'],
            ['Índigo Corporativo', 'indigo-corporativo', '#6366f1', '#3730a3', '#ffffff', '#eef2ff', '#1e1b4b', '#a5b4fc'],
            ['Âmbar Premium', 'ambar-premium', '#f59e0b', '#92400e', '#ffffff', '#fffbeb', '#1f2937', '#fbbf24'],
            ['Cinza Minimalista', 'cinza-minimalista', '#6b7280', '#374151', '#ffffff', '#f9fafb', '#111827', '#d1d5db'],
        ];

        $activePaletteName = 'Azul Profissional';

        foreach ($palettes as $index => $palette) {
            ThemePalette::query()->updateOrCreate(
                ['name' => $palette[0]],
                [
                    'slug' => $palette[1],
                    'primary_color' => $palette[2],
                    'secondary_color' => $palette[3],
                    'surface_color' => $palette[4],
                    'background_color' => $palette[5],
                    'text_color' => $palette[6],
                    'accent_color' => $palette[7],
                    'success_color' => '#16a34a',
                    'warning_color' => '#f59e0b',
                    'danger_color' => '#dc2626',
                    'info_color' => '#0284c7',
                    'header_color' => $palette[2],
                    'footer_color' => $palette[3],
                    'button_color' => $palette[2],
                    'link_color' => $palette[3],
                    'card_color' => $palette[4],
                    'border_color' => '#e5e7eb',
                    'hover_color' => $palette[3],
                    'focus_color' => $palette[7],
                    'dark_background_color' => '#020617',
                    'dark_surface_color' => '#0f172a',
                    'is_active' => $palette[0] === $activePaletteName,
                    'is_current' => $palette[0] === $activePaletteName,
                    'position' => $index + 1,
                ]
            );
        }

        ThemePalette::query()->where('name', '!=', $activePaletteName)->update([
            'is_active' => false,
            'is_current' => false,
        ]);
    }
}


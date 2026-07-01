<?php

namespace Database\Seeders;

use App\Models\ThemePalette;
use Illuminate\Database\Seeder;

class ThemePaletteSeeder extends Seeder
{
    public function run(): void
    {
        $palettes = [
            ['Azul Corporativo', '#1d4ed8', '#0f172a', '#ffffff', '#eff6ff', '#0f172a', '#38bdf8'],
            ['Azul Escuro', '#172554', '#1e3a8a', '#ffffff', '#dbeafe', '#0f172a', '#60a5fa'],
            ['Verde Saúde', '#15803d', '#16a34a', '#ffffff', '#f0fdf4', '#052e16', '#86efac'],
            ['Verde Natureza', '#166534', '#84cc16', '#ffffff', '#f7fee7', '#052e16', '#bef264'],
            ['Roxo Moderno', '#6d28d9', '#a855f7', '#ffffff', '#faf5ff', '#1f2937', '#d8b4fe'],
            ['Vermelho Elegante', '#991b1b', '#dc2626', '#ffffff', '#fef2f2', '#1f2937', '#fca5a5'],
            ['Cinza Executivo', '#374151', '#6b7280', '#ffffff', '#f9fafb', '#111827', '#d1d5db'],
            ['Dourado Premium', '#92400e', '#d97706', '#ffffff', '#fffbeb', '#1f2937', '#fbbf24'],
            ['Preto Luxo', '#020617', '#111827', '#ffffff', '#f8fafc', '#020617', '#f59e0b'],
            ['Branco Clean', '#0f766e', '#64748b', '#ffffff', '#ffffff', '#0f172a', '#14b8a6'],
            ['Azul Petróleo', '#164e63', '#0e7490', '#ffffff', '#ecfeff', '#0f172a', '#22d3ee'],
            ['Azul Céu', '#0284c7', '#38bdf8', '#ffffff', '#f0f9ff', '#0f172a', '#7dd3fc'],
            ['Laranja Energia', '#c2410c', '#f97316', '#ffffff', '#fff7ed', '#1f2937', '#fdba74'],
            ['Coral', '#be123c', '#fb7185', '#ffffff', '#fff1f2', '#1f2937', '#fda4af'],
            ['Verde Esmeralda', '#047857', '#10b981', '#ffffff', '#ecfdf5', '#022c22', '#6ee7b7'],
            ['Marrom Madeira', '#78350f', '#a16207', '#fffaf5', '#fef3c7', '#1f2937', '#fcd34d'],
            ['Turquesa', '#0f766e', '#2dd4bf', '#ffffff', '#f0fdfa', '#134e4a', '#99f6e4'],
            ['Índigo', '#3730a3', '#6366f1', '#ffffff', '#eef2ff', '#1e1b4b', '#a5b4fc'],
            ['Rosa Moderno', '#be185d', '#ec4899', '#ffffff', '#fdf2f8', '#1f2937', '#f9a8d4'],
            ['CERAPE Original', '#0f172a', '#155e75', '#ffffff', '#f8fafc', '#0f172a', '#38bdf8'],
        ];

        foreach ($palettes as $index => $palette) {
            ThemePalette::query()->updateOrCreate(
                ['name' => $palette[0]],
                [
                    'primary_color' => $palette[1],
                    'secondary_color' => $palette[2],
                    'surface_color' => $palette[3],
                    'background_color' => $palette[4],
                    'text_color' => $palette[5],
                    'accent_color' => $palette[6],
                    'success_color' => '#16a34a',
                    'warning_color' => '#f59e0b',
                    'danger_color' => '#dc2626',
                    'info_color' => '#0284c7',
                    'header_color' => $palette[1],
                    'footer_color' => $palette[2],
                    'button_color' => $palette[1],
                    'link_color' => $palette[2],
                    'card_color' => $palette[3],
                    'border_color' => '#e5e7eb',
                    'hover_color' => $palette[2],
                    'focus_color' => $palette[6],
                    'dark_background_color' => '#020617',
                    'dark_surface_color' => '#0f172a',
                    'is_active' => true,
                    'is_current' => $palette[0] === 'CERAPE Original',
                    'position' => $index + 1,
                ]
            );
        }
    }
}

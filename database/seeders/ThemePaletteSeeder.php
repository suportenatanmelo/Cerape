<?php

namespace Database\Seeders;

use App\Models\ThemePalette;
use Illuminate\Database\Seeder;

class ThemePaletteSeeder extends Seeder
{
    public function run(): void
    {
        $palettes = [
            ['Acolhimento Sereno', '#0f172a', '#155e75', '#ffffff', '#f8fafc', '#0f172a', '#38bdf8'],
            ['Esperanca Clara', '#14532d', '#22c55e', '#ffffff', '#f0fdf4', '#052e16', '#84cc16'],
            ['Calma Hospitalar', '#1e3a8a', '#3b82f6', '#ffffff', '#eff6ff', '#0f172a', '#60a5fa'],
            ['Terra e Cuidado', '#7c2d12', '#ea580c', '#fffaf5', '#fff7ed', '#1f2937', '#fb923c'],
            ['Reset Humano', '#312e81', '#8b5cf6', '#ffffff', '#faf5ff', '#111827', '#c084fc'],
            ['Renovo Azul', '#0f766e', '#14b8a6', '#ffffff', '#f0fdfa', '#134e4a', '#2dd4bf'],
            ['Luz de Recomeço', '#92400e', '#f59e0b', '#ffffff', '#fffbeb', '#1f2937', '#fbbf24'],
            ['Tons de Paz', '#1f2937', '#64748b', '#ffffff', '#f8fafc', '#111827', '#94a3b8'],
            ['Verde Tratamento', '#14532d', '#16a34a', '#ffffff', '#f0fdf4', '#052e16', '#86efac'],
            ['Horizonte Limpo', '#164e63', '#06b6d4', '#ffffff', '#ecfeff', '#0f172a', '#67e8f9'],
            ['Refugio', '#3f3cbb', '#6366f1', '#ffffff', '#eef2ff', '#1e1b4b', '#a5b4fc'],
            ['Base Neutra', '#111827', '#6b7280', '#ffffff', '#f9fafb', '#111827', '#d1d5db'],
            ['Nutrir', '#166534', '#22c55e', '#ffffff', '#f7fee7', '#14532d', '#bef264'],
            ['Cura Solar', '#9a3412', '#f97316', '#fffaf5', '#fff7ed', '#431407', '#fdba74'],
            ['Apoio Profundo', '#312e81', '#4338ca', '#ffffff', '#eef2ff', '#111827', '#818cf8'],
            ['Porto Seguro', '#0f172a', '#0ea5e9', '#ffffff', '#eff6ff', '#0f172a', '#7dd3fc'],
            ['Brisa Verde', '#064e3b', '#10b981', '#ffffff', '#f0fdf4', '#022c22', '#6ee7b7'],
            ['Gentileza', '#7c3aed', '#a855f7', '#ffffff', '#faf5ff', '#1f2937', '#d8b4fe'],
            ['Esperar e Crescer', '#854d0e', '#eab308', '#ffffff', '#fffbeb', '#1f2937', '#facc15'],
            ['Caminho Limpo', '#1d4ed8', '#38bdf8', '#ffffff', '#eff6ff', '#0f172a', '#bae6fd'],
            ['Ritmo Suave', '#1e293b', '#475569', '#ffffff', '#f8fafc', '#0f172a', '#94a3b8'],
            ['Verde Vida', '#166534', '#4ade80', '#ffffff', '#f0fdf4', '#052e16', '#86efac'],
            ['Aurora Clínica', '#0f766e', '#2dd4bf', '#ffffff', '#f0fdfa', '#134e4a', '#99f6e4'],
            ['Foco e Fluxo', '#7e22ce', '#c084fc', '#ffffff', '#faf5ff', '#111827', '#e9d5ff'],
            ['Mente Aberta', '#0f172a', '#818cf8', '#ffffff', '#eef2ff', '#111827', '#c7d2fe'],
            ['Cuidado Solar', '#b45309', '#fb923c', '#fffaf5', '#fff7ed', '#1f2937', '#fdba74'],
            ['Saida Segura', '#1e40af', '#60a5fa', '#ffffff', '#eff6ff', '#0f172a', '#93c5fd'],
            ['Fresco e Humano', '#065f46', '#34d399', '#ffffff', '#f0fdf4', '#022c22', '#a7f3d0'],
            ['Equilibrio', '#374151', '#9ca3af', '#ffffff', '#f9fafb', '#111827', '#d1d5db'],
            ['Caminho de Paz', '#155e75', '#22d3ee', '#ffffff', '#ecfeff', '#0f172a', '#a5f3fc'],
            ['Nova Rotina', '#4c1d95', '#8b5cf6', '#ffffff', '#faf5ff', '#1f2937', '#c4b5fd'],
            ['Abraço Visual', '#166534', '#84cc16', '#ffffff', '#f7fee7', '#052e16', '#bef264'],
            ['Saude Clara', '#1d4ed8', '#2563eb', '#ffffff', '#eff6ff', '#172554', '#93c5fd'],
            ['Recuperar', '#7c2d12', '#ef4444', '#fffaf5', '#fff1f2', '#431407', '#fca5a5'],
            ['Amanhecer', '#1e40af', '#22c55e', '#ffffff', '#eff6ff', '#0f172a', '#86efac'],
            ['Essencia', '#3b0764', '#a855f7', '#ffffff', '#faf5ff', '#111827', '#d8b4fe'],
            ['Navegar', '#0f766e', '#f59e0b', '#ffffff', '#f0fdfa', '#134e4a', '#fcd34d'],
            ['Voltar ao Centro', '#111827', '#f97316', '#ffffff', '#f9fafb', '#111827', '#fdba74'],
            ['Respirar', '#1d4ed8', '#14b8a6', '#ffffff', '#eff6ff', '#0f172a', '#99f6e4'],
            ['Renascer', '#14532d', '#facc15', '#ffffff', '#f7fee7', '#052e16', '#fde047'],
            ['Cuidar Bem', '#0f172a', '#f43f5e', '#ffffff', '#fff1f2', '#111827', '#fda4af'],
            ['Humanidade', '#334155', '#38bdf8', '#ffffff', '#f8fafc', '#0f172a', '#7dd3fc'],
            ['Clareza', '#1e3a8a', '#a855f7', '#ffffff', '#eef2ff', '#111827', '#c4b5fd'],
            ['Aurora Verde', '#14532d', '#22c55e', '#ffffff', '#f0fdf4', '#052e16', '#86efac'],
            ['Sentido', '#7c3aed', '#f472b6', '#ffffff', '#faf5ff', '#1f2937', '#f9a8d4'],
            ['Caminho Solar', '#92400e', '#fbbf24', '#fffaf5', '#fffbeb', '#1f2937', '#fcd34d'],
            ['Ressonar', '#064e3b', '#2dd4bf', '#ffffff', '#f0fdfa', '#022c22', '#99f6e4'],
            ['Tranquilidade', '#334155', '#38bdf8', '#ffffff', '#f8fafc', '#0f172a', '#7dd3fc'],
            ['Renovo Natural', '#166534', '#84cc16', '#ffffff', '#f7fee7', '#052e16', '#d9f99d'],
            ['Acolher e Avancar', '#0f172a', '#f97316', '#ffffff', '#f8fafc', '#111827', '#fdba74'],
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
                'is_active' => true,
                'position' => $index + 1,
                ]
            );
        }
    }
}

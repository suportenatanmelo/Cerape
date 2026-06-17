<?php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

final class FrontendThemePresets
{
    /**
     * @return array<string, array<string, string>>
     */
    public static function profiles(): array
    {
        return [
            'clinica-branco' => [
                'name' => 'Clínica Branco',
                'description' => 'Base clara e limpa, com sensação clínica, acolhedora e muito leve.',
                'primary_color' => '#6b8f82',
                'secondary_color' => '#a8bfae',
                'accent_color' => '#d6c7a1',
                'background_color' => '#f8fbfa',
                'surface_color' => '#ffffff',
                'surface_strong_color' => '#edf4f1',
                'ink_color' => '#24413a',
                'text_color' => '#24413a',
                'muted_color' => '#6f7f79',
                'body_font' => 'Manrope',
                'display_font' => 'Cormorant Garamond',
            ],
            'branco-areia' => [
                'name' => 'Branco Areia',
                'description' => 'Tons suaves de areia e branco para uma leitura tranquila e elegante.',
                'primary_color' => '#b08d57',
                'secondary_color' => '#cbb89d',
                'accent_color' => '#8fb4a5',
                'background_color' => '#fffdf8',
                'surface_color' => '#ffffff',
                'surface_strong_color' => '#f5efe4',
                'ink_color' => '#3f3325',
                'text_color' => '#3f3325',
                'muted_color' => '#7f7361',
                'body_font' => 'Plus Jakarta Sans',
                'display_font' => 'Fraunces',
            ],
            'azul-nevoa' => [
                'name' => 'Azul Névoa',
                'description' => 'Visual suave em azul claro, ideal para transmitir serenidade e confiança.',
                'primary_color' => '#7aa5c9',
                'secondary_color' => '#bdd6ea',
                'accent_color' => '#90c3c8',
                'background_color' => '#f7fbff',
                'surface_color' => '#ffffff',
                'surface_strong_color' => '#e7f1f9',
                'ink_color' => '#223044',
                'text_color' => '#223044',
                'muted_color' => '#6e7f8f',
                'body_font' => 'DM Sans',
                'display_font' => 'Playfair Display',
            ],
            'verde-claro' => [
                'name' => 'Verde Claro',
                'description' => 'Tons de cuidado e estabilidade, com clima terapêutico e fresco.',
                'primary_color' => '#8fb09a',
                'secondary_color' => '#c8ddcf',
                'accent_color' => '#d9e7cb',
                'background_color' => '#fbfefb',
                'surface_color' => '#ffffff',
                'surface_strong_color' => '#eef6ee',
                'ink_color' => '#294436',
                'text_color' => '#294436',
                'muted_color' => '#6a7d72',
                'body_font' => 'Outfit',
                'display_font' => 'Space Grotesk',
            ],
            'perola' => [
                'name' => 'Pérola',
                'description' => 'Neutro sofisticado, com muito espaço em branco e toque acolhedor.',
                'primary_color' => '#c9c7c1',
                'secondary_color' => '#e3dfd7',
                'accent_color' => '#9db9aa',
                'background_color' => '#ffffff',
                'surface_color' => '#ffffff',
                'surface_strong_color' => '#f4f4f0',
                'ink_color' => '#373737',
                'text_color' => '#373737',
                'muted_color' => '#767676',
                'body_font' => 'Urbanist',
                'display_font' => 'Libre Baskerville',
            ],
            'luz-macia' => [
                'name' => 'Luz Macia',
                'description' => 'Um visual quase hospitalar, muito limpo, com azul e cinza delicados.',
                'primary_color' => '#86a8c2',
                'secondary_color' => '#d9e5ee',
                'accent_color' => '#b7c7cf',
                'background_color' => '#fcfdff',
                'surface_color' => '#ffffff',
                'surface_strong_color' => '#eef3f7',
                'ink_color' => '#274157',
                'text_color' => '#274157',
                'muted_color' => '#728394',
                'body_font' => 'Manrope',
                'display_font' => 'Space Grotesk',
            ],
            'acolhedor' => [
                'name' => 'Acolhedor',
                'description' => 'Palette quente e elegante, com atmosfera próxima e humana.',
                'primary_color' => '#c97a2b',
                'secondary_color' => '#7a4b21',
                'accent_color' => '#2f6b45',
                'background_color' => '#140f05',
                'surface_color' => '#fff7ed',
                'surface_strong_color' => '#f4e6d7',
                'ink_color' => '#140f05',
                'text_color' => '#fff7ed',
                'muted_color' => '#e7d6bf',
                'body_font' => 'Manrope',
                'display_font' => 'Cormorant Garamond',
            ],
            'sereno' => [
                'name' => 'Sereno',
                'description' => 'Visual leve com azuis profundos e leitura limpa para ambientes institucionais.',
                'primary_color' => '#1d4ed8',
                'secondary_color' => '#155e75',
                'accent_color' => '#0f766e',
                'background_color' => '#08111f',
                'surface_color' => '#eef6ff',
                'surface_strong_color' => '#dbeafe',
                'ink_color' => '#08111f',
                'text_color' => '#eef6ff',
                'muted_color' => '#c8d7eb',
                'body_font' => 'Plus Jakarta Sans',
                'display_font' => 'Playfair Display',
            ],
            'jardim' => [
                'name' => 'Jardim',
                'description' => 'Tons naturais com foco em acolhimento, estabilidade e frescor visual.',
                'primary_color' => '#3f6212',
                'secondary_color' => '#166534',
                'accent_color' => '#b45309',
                'background_color' => '#101a0f',
                'surface_color' => '#f7fee7',
                'surface_strong_color' => '#dcfce7',
                'ink_color' => '#101a0f',
                'text_color' => '#f7fee7',
                'muted_color' => '#dce8c9',
                'body_font' => 'DM Sans',
                'display_font' => 'Fraunces',
            ],
            'solar' => [
                'name' => 'Solar',
                'description' => 'Energia suave, contraste alto e um desenho mais contemporâneo.',
                'primary_color' => '#d97706',
                'secondary_color' => '#9f1239',
                'accent_color' => '#4f46e5',
                'background_color' => '#1b1208',
                'surface_color' => '#fff7ed',
                'surface_strong_color' => '#fed7aa',
                'ink_color' => '#1b1208',
                'text_color' => '#fff7ed',
                'muted_color' => '#f0e0c8',
                'body_font' => 'Outfit',
                'display_font' => 'Space Grotesk',
            ],
            'contemporaneo' => [
                'name' => 'Contemporâneo',
                'description' => 'Perfil mais editorial, com presença sofisticada e institucional.',
                'primary_color' => '#be123c',
                'secondary_color' => '#4338ca',
                'accent_color' => '#0f766e',
                'background_color' => '#1a1020',
                'surface_color' => '#fff1f5',
                'surface_strong_color' => '#fbcfe8',
                'ink_color' => '#1a1020',
                'text_color' => '#fff1f5',
                'muted_color' => '#e5c5d9',
                'body_font' => 'Urbanist',
                'display_font' => 'Libre Baskerville',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function presetOptions(): array
    {
        return collect(static::profiles())
            ->mapWithKeys(fn (array $profile, string $key): array => [$key => $profile['name']])
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public static function fontOptions(): array
    {
        return [
            'Manrope' => 'Manrope',
            'Plus Jakarta Sans' => 'Plus Jakarta Sans',
            'DM Sans' => 'DM Sans',
            'Outfit' => 'Outfit',
            'Urbanist' => 'Urbanist',
            'Cormorant Garamond' => 'Cormorant Garamond',
            'Playfair Display' => 'Playfair Display',
            'Fraunces' => 'Fraunces',
            'Space Grotesk' => 'Space Grotesk',
            'Libre Baskerville' => 'Libre Baskerville',
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function fontFamilies(): array
    {
        return array_values(array_unique(Arr::flatten(array_map(
            fn (array $profile): array => [$profile['body_font'], $profile['display_font']],
            static::profiles()
        ))));
    }

    public static function googleFontsUrl(): string
    {
        $families = collect(static::fontFamilies())
            ->map(fn (string $family): string => 'family=' . Str::of($family)->replace(' ', '+')->value() . ':wght@400;500;600;700;800')
            ->implode('&');

        return 'https://fonts.googleapis.com/css2?' . $families . '&display=swap';
    }

    /**
     * @return array<string, string>|null
     */
    public static function profile(?string $key): ?array
    {
        if (! is_string($key)) {
            return null;
        }

        return static::profiles()[$key] ?? null;
    }

    public static function defaultProfileKey(): string
    {
        return array_key_first(static::profiles()) ?: 'acolhedor';
    }
}

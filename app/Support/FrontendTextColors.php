<?php

namespace App\Support;

use Filament\Forms\Components\RichEditor\TextColor;

final class FrontendTextColors
{
    /**
     * @return array<string, string|TextColor>
     */
    public static function palette(): array
    {
        return [
            'ipe-yellow' => TextColor::make('Ipê amarelo', '#f2c94c', darkColor: '#f5d76c'),
            'earth-brown' => TextColor::make('Marrom terra', '#8a6b34', darkColor: '#a27a3f'),
            'deep-ink' => TextColor::make('Tinta escura', '#140f05', darkColor: '#140f05'),
            'forest-green' => TextColor::make('Verde folha', '#6f7f1b', darkColor: '#8da120'),
            'slate' => TextColor::make('Cinza chumbo', '#1f2937', darkColor: '#334155'),
            'white' => TextColor::make('Branco', '#ffffff', darkColor: '#ffffff'),
            ...TextColor::getDefaults(),
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function paletteExamples(): array
    {
        return [
            '#f2c94c',
            '#8a6b34',
            '#140f05',
            '#6f7f1b',
            '#1f2937',
            '#ffffff',
        ];
    }
}

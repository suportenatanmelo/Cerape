<?php

namespace App\Filament\Frontend\Resources\ThemeProfiles\Schemas;

use App\Support\FrontendThemePresets;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ThemeProfileForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Perfil visual')
                    ->description('Escolha um perfil pronto, depois personalize cores e tipografia com liberdade.')
                    ->icon('heroicon-o-swatch')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Select::make('preset_key')
                                ->label('Perfil base')
                                ->options(FrontendThemePresets::presetOptions())
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function (Set $set, mixed $state): void {
                                    $preset = FrontendThemePresets::profile(is_string($state) ? $state : null);

                                    if ($preset === null) {
                                        return;
                                    }

                                    $set('name', $preset['name']);
                                    $set('description', $preset['description']);
                                    $set('primary_color', $preset['primary_color']);
                                    $set('secondary_color', $preset['secondary_color']);
                                    $set('accent_color', $preset['accent_color']);
                                    $set('background_color', $preset['background_color']);
                                    $set('surface_color', $preset['surface_color']);
                                    $set('surface_strong_color', $preset['surface_strong_color']);
                                    $set('ink_color', $preset['ink_color']);
                                    $set('text_color', $preset['text_color']);
                                    $set('muted_color', $preset['muted_color']);
                                    $set('body_font', $preset['body_font']);
                                    $set('display_font', $preset['display_font']);
                                })
                                ->helperText('Os 10 perfis prontos ajudam a manter uma identidade visual consistente e acolhedora.'),
                            Toggle::make('is_active')
                                ->label('Ativar este perfil')
                                ->default(false)
                                ->inline(false),
                            TextInput::make('name')
                                ->label('Nome do perfil')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, mixed $state): void {
                                    if (filled($state)) {
                                        $set('slug', Str::slug((string) $state));
                                    }
                                })
                                ->placeholder('Ex.: Acolhedor'),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText('Usado internamente para identificar o perfil visual.'),
                            Textarea::make('description')
                                ->label('Descricao')
                                ->rows(4)
                                ->placeholder('Explique o clima visual e a proposta estetica deste perfil.')
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Cores')
                    ->description('Aplique a paleta principal do layout com contraste profissional e elegante.')
                    ->icon('heroicon-o-paint-brush')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 3,
                        ])->schema([
                            ColorPicker::make('primary_color')
                                ->label('Cor primaria')
                                ->required(),
                            ColorPicker::make('secondary_color')
                                ->label('Cor secundaria')
                                ->required(),
                            ColorPicker::make('accent_color')
                                ->label('Cor de destaque')
                                ->required(),
                            ColorPicker::make('background_color')
                                ->label('Cor do fundo')
                                ->required(),
                            ColorPicker::make('surface_color')
                                ->label('Cor das superficies')
                                ->required(),
                            ColorPicker::make('surface_strong_color')
                                ->label('Cor das superficies fortes')
                                ->required(),
                            ColorPicker::make('ink_color')
                                ->label('Cor da tipografia')
                                ->required(),
                            ColorPicker::make('text_color')
                                ->label('Cor do texto')
                                ->required(),
                            ColorPicker::make('muted_color')
                                ->label('Cor secundária do texto')
                                ->required(),
                        ]),
                    ]),
                Section::make('Tipografia')
                    ->description('Defina a combinacao de fontes que sera aplicada ao corpo e aos titulos.')
                    ->icon('heroicon-o-language')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            Select::make('body_font')
                                ->label('Fonte do texto')
                                ->options(FrontendThemePresets::fontOptions())
                                ->searchable()
                                ->required(),
                            Select::make('display_font')
                                ->label('Fonte dos titulos')
                                ->options(FrontendThemePresets::fontOptions())
                                ->searchable()
                                ->required(),
                        ]),
                    ]),
            ]);
    }
}

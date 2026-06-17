<?php

namespace App\Filament\Frontend\Resources\FooterSettings\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class FooterSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Conteudo do rodape')
                    ->description('Defina a mensagem institucional, contatos e links que aparecem no rodape do site.')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('brand_name')
                                ->label('Nome da marca')
                                ->placeholder('Ex.: CERAPE')
                                ->maxLength(255),
                            TextInput::make('copyright_text')
                                ->label('Texto de copyright')
                                ->placeholder('Ex.: CERAPE. Todos os direitos reservados.')
                                ->maxLength(255),
                            TextInput::make('address')
                                ->label('Endereco')
                                ->placeholder('Rua, numero, bairro, cidade')
                                ->default('Fazenda - R. 5 A - Parque Alvorada III, Luziânia - GO, 72859-899')
                                ->helperText('Este endereco pode ser usado no rodape, no mapa e como fallback do contato.')
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('E-mail')
                                ->email()
                                ->placeholder('contato@cerape.local')
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Telefone')
                                ->placeholder('(00) 00000-0000')
                                ->maxLength(255),
                            TextInput::make('whatsapp')
                                ->label('WhatsApp')
                                ->placeholder('(00) 00000-0000')
                                ->maxLength(255),
                            Textarea::make('map_embed_code')
                                ->label('Mapa do Google')
                                ->placeholder('<iframe src="https://www.google.com/maps/embed?..."></iframe>')
                                ->helperText('Cole aqui o iframe completo ou a URL de embed. O site vai interpretar automaticamente.')
                                ->rows(7)
                                ->columnSpanFull(),
                            TextInput::make('map_embed_url')
                                ->label('Link curto do mapa')
                                ->placeholder('https://www.google.com/maps/embed?...')
                                ->helperText('Opcional. Use apenas se preferir salvar a URL sem o iframe completo.')
                                ->columnSpanFull()
                                ->maxLength(255),
                            Textarea::make('tagline')
                                ->label('Descricao do rodape')
                                ->placeholder('Mensagem curta para apresentar o site e a equipe.')
                                ->rows(4)
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Links e redes')
                    ->description('Monte um conjunto de links rapidos e redes sociais com visual mais elegante.')
                    ->icon('heroicon-o-link')
                    ->schema([
                        Repeater::make('quick_links')
                            ->label('Links rapidos')
                            ->addActionLabel('Adicionar link')
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                ])->schema([
                                    TextInput::make('label')
                                        ->label('Rotulo')
                                        ->placeholder('Ex.: Blog')
                                        ->required()
                                        ->maxLength(120),
                                    TextInput::make('url')
                                        ->label('URL')
                                        ->placeholder('Ex.: /blog ou https://...')
                                        ->required()
                                        ->maxLength(255),
                                ]),
                            ])
                            ->columnSpanFull(),
                        Repeater::make('social_links')
                            ->label('Redes sociais')
                            ->addActionLabel('Adicionar rede')
                            ->reorderable()
                            ->collapsible()
                            ->defaultItems(0)
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                ])->schema([
                                    TextInput::make('label')
                                        ->label('Nome')
                                        ->placeholder('Ex.: Instagram')
                                        ->required()
                                        ->maxLength(120),
                                    TextInput::make('url')
                                        ->label('URL')
                                        ->placeholder('https://instagram.com/...') 
                                        ->required()
                                        ->url()
                                        ->maxLength(255),
                                ]),
                            ])
                            ->columnSpanFull(),
                    ]),
                Section::make('Aparencia')
                    ->description('Use a cor do tema selecionado por padrao ou personalize o rodape com uma identidade propria.')
                    ->icon('heroicon-o-paint-brush')
                    ->schema([
                        Toggle::make('use_theme_colors')
                            ->label('Usar cores do tema')
                            ->default(true)
                            ->live()
                            ->inline(false),
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            ColorPicker::make('background_color')
                                ->label('Cor do fundo')
                                ->visible(fn (Get $get): bool => ! (bool) $get('use_theme_colors')),
                            ColorPicker::make('text_color')
                                ->label('Cor do texto')
                                ->visible(fn (Get $get): bool => ! (bool) $get('use_theme_colors')),
                            ColorPicker::make('muted_color')
                                ->label('Cor secundaria do texto')
                                ->visible(fn (Get $get): bool => ! (bool) $get('use_theme_colors')),
                            ColorPicker::make('border_color')
                                ->label('Cor da borda')
                                ->visible(fn (Get $get): bool => ! (bool) $get('use_theme_colors')),
                        ]),
                    ]),
            ]);
    }
}

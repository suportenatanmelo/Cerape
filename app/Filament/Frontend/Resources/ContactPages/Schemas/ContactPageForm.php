<?php

namespace App\Filament\Frontend\Resources\ContactPages\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Support\FrontendTextColors;
use App\Support\ImageStorageNaming;

class ContactPageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Cabecalho da pagina')
                    ->description('Configure a apresentacao principal da pagina de contato.')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('title')
                                ->label('Titulo')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, mixed $state): void {
                                    $set('slug', Str::slug((string) $state));
                                })
                                ->maxLength(255)
                                ->placeholder('Ex.: Fale com a equipe'),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText('Usado para identificar a pagina no painel.'),
                            TextInput::make('subtitle')
                                ->label('Subtitulo')
                                ->placeholder('Ex.: Vamos falar sobre sua necessidade')
                                ->maxLength(255),
                            Toggle::make('is_active')
                                ->label('Pagina ativa')
                                ->default(true)
                                ->inline(false),
                            RichEditor::make('intro')
                                ->label('Introducao')
                                ->toolbarButtons([
                                    'bold',
                                    'italic',
                                    'textColor',
                                    'link',
                                    'bulletList',
                                    'orderedList',
                                    'redo',
                                    'undo',
                                ])
                                ->placeholder('Escreva o texto que aparece antes dos cards de contato.')
                                ->helperText('Selecione um trecho e escolha a cor do texto. Paleta sugerida: ' . implode(', ', FrontendTextColors::paletteExamples()) . '.')
                                ->textColors(FrontendTextColors::palette())
                                ->customTextColors()
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Imagem de destaque')
                    ->description('Uma imagem suave ajuda a dar mais identidade para a pagina.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            FileUpload::make('hero_image')
                                ->label('Imagem')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory(ImageStorageNaming::datedDirectory('frontend/contact'))
                                ->visibility('public')
                                ->downloadable()
                                ->openable()
                                ->maxFiles(1)
                                ->maxSize(4096)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->getUploadedFileNameForStorageUsing(
                                    fn (TemporaryUploadedFile $file, Get $get): string => ImageStorageNaming::filename(
                                        $file,
                                        'frontend-contact',
                                        (string) $get('title'),
                                    )
                                ),
                            TextInput::make('hero_image_alt')
                                ->label('Descricao da imagem')
                                ->placeholder('Ex.: Equipe CERAPE em atendimento')
                                ->maxLength(255),
                        ]),
                    ]),
                Section::make('Canais de contato')
                    ->description('Inclua os dados que vao aparecer nos cards da pagina.')
                    ->icon('heroicon-o-envelope')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
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
                            TextInput::make('address')
                                ->label('Endereco')
                                ->placeholder('Rua, numero, bairro, cidade')
                                ->default('Fazenda - R. 5 A - Parque Alvorada III, Luziânia - GO, 72859-899')
                                ->helperText('Use o mesmo endereco do rodape para manter o site centralizado.')
                                ->maxLength(255),
                            TextInput::make('opening_hours')
                                ->label('Horario de atendimento')
                                ->placeholder('Seg. a Sex., 08h as 17h')
                                ->maxLength(255),
                            Textarea::make('map_embed_code')
                                ->label('Mapa do Google')
                                ->placeholder('<iframe src="https://www.google.com/maps/embed?..."></iframe>')
                                ->helperText('Cole o iframe completo ou a URL de embed. O site vai converter automaticamente.')
                                ->rows(7)
                                ->columnSpanFull(),
                            TextInput::make('cta_label')
                                ->label('Texto do botao')
                                ->placeholder('Ex.: Abrir contato')
                                ->maxLength(255),
                            TextInput::make('cta_url')
                                ->label('Link do botao')
                                ->placeholder('Ex.: mailto:contato@cerape.local')
                                ->maxLength(255),
                            TextInput::make('map_embed_url')
                                ->label('Link curto do mapa')
                                ->placeholder('https://www.google.com/maps/embed?...')
                                ->helperText('Opcional. Se quiser salvar apenas a URL, use este campo.')
                                ->columnSpanFull()
                                ->maxLength(255),
                            Repeater::make('social_links')
                                ->label('Links sociais')
                                ->addActionLabel('Adicionar link')
                                ->collapsible()
                                ->reorderable()
                                ->defaultItems(0)
                                ->itemLabel(fn (array $state): ?string => filled($state['label'] ?? null) ? $state['label'] : 'Novo link')
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
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Frontend\Resources\CarouselSlides\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
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

class CarouselSlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Conteudo do slide')
                    ->description('Defina o texto principal do slide, o identificador e a chamada.')
                    ->icon('heroicon-o-rectangle-stack')
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
                                ->placeholder('Ex.: Ipê Amarelo em destaque'),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText('Usado para identificar o slide internamente.'),
                            TextInput::make('eyebrow')
                                ->label('Etiqueta')
                                ->placeholder('Ex.: Destaque institucional')
                                ->maxLength(120),
                            ColorPicker::make('eyebrow_color')
                                ->label('Cor da etiqueta')
                                ->default('#f2c94c')
                                ->helperText('Ex.: #f2c94c, #ffffff ou #140f05.'),
                            TextInput::make('cta_label')
                                ->label('Texto do botao')
                                ->placeholder('Ex.: Saiba mais')
                                ->maxLength(255),
                            ColorPicker::make('cta_text_color')
                                ->label('Cor do texto do botao')
                                ->default('#140f05')
                                ->helperText('Ex.: #140f05, #ffffff ou #1f2937.'),
                            TextInput::make('cta_url')
                                ->label('Link do botao')
                                ->placeholder('Ex.: #contact ou https://site.com')
                                ->maxLength(255),
                            ColorPicker::make('title_color')
                                ->label('Cor do titulo')
                                ->default('#ffffff')
                                ->helperText('Ex.: #ffffff, #f2c94c ou #1f2937.'),
                            ColorPicker::make('description_color')
                                ->label('Cor da descricao')
                                ->default('#e5e7eb')
                                ->helperText('Ex.: #e5e7eb, #f9dd84 ou #1f2937.'),
                            TextInput::make('sort_order')
                                ->label('Ordem')
                                ->numeric()
                                ->default(0)
                                ->helperText('Slides com menor numero aparecem primeiro.')
                                ->maxLength(10),
                            Toggle::make('is_active')
                                ->label('Slide ativo')
                                ->default(true)
                                ->inline(false),
                            RichEditor::make('description')
                                ->label('Descricao')
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
                                ->placeholder('Conte a mensagem do slide em poucas linhas.')
                                ->helperText('Selecione um trecho e use a cor do texto. Paleta sugerida: ' . implode(', ', FrontendTextColors::paletteExamples()) . '.')
                                ->textColors(FrontendTextColors::palette())
                                ->customTextColors()
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Imagem')
                    ->description('Escolha uma imagem horizontal com boa leitura visual.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            FileUpload::make('image')
                                ->label('Imagem do slide')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory(ImageStorageNaming::datedDirectory('frontend/carousel'))
                                ->visibility('public')
                                ->downloadable()
                                ->openable()
                                ->maxFiles(1)
                                ->maxSize(4096)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->getUploadedFileNameForStorageUsing(
                                    fn (TemporaryUploadedFile $file, Get $get): string => ImageStorageNaming::filename(
                                        $file,
                                        'frontend-carousel',
                                        (string) $get('title'),
                                    )
                                )
                                ->required(),
                            TextInput::make('image_alt')
                                ->label('Descricao da imagem')
                                ->placeholder('Ex.: Arvore de ipê amarelo em destaque')
                                ->maxLength(255),
                        ]),
                    ]),
            ]);
    }
}

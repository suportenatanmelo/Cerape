<?php

namespace App\Filament\Frontend\Resources\BlogPosts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
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

class BlogPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informacoes do post')
                    ->description('Configure titulo, slug, autor e status de publicacao.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            TextInput::make('title')
                                ->label('Titulo')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (Set $set, mixed $state): void {
                                    $set('slug', Str::slug((string) $state));
                                })
                                ->placeholder('Ex.: CERAPE reforca a integracao com familias'),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255)
                                ->helperText('Pode ser ajustado manualmente se necessario.'),
                            TextInput::make('category')
                                ->label('Categoria')
                                ->placeholder('Ex.: Institucional, Noticias, Eventos')
                                ->maxLength(120),
                            TextInput::make('author_name')
                                ->label('Autor')
                                ->default('CERAPE')
                                ->maxLength(150),
                            Select::make('author_id')
                                ->label('Perfil do autor')
                                ->relationship('author', 'name')
                                ->searchable()
                                ->preload()
                                ->helperText('Quando selecionado, o blog mostra foto e dados do perfil do usuario.')
                                ->columnSpanFull(),
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'draft' => 'Rascunho',
                                    'published' => 'Publicado',
                                ])
                                ->default('draft')
                                ->required(),
                            Toggle::make('is_featured')
                                ->label('Destacar no site')
                                ->default(false)
                                ->inline(false),
                            DateTimePicker::make('published_at')
                                ->label('Data de publicacao')
                                ->seconds(false)
                                ->native(false)
                                ->placeholder('Selecione a data de publicacao')
                                ->helperText('Use uma data futura se quiser agendar a publicacao.')
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Capa e resumo')
                    ->description('Adicione a imagem de destaque e um resumo curto para cards e previews.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            FileUpload::make('cover_image')
                                ->label('Imagem de capa')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->directory(ImageStorageNaming::datedDirectory('frontend/blog/posts'))
                                ->visibility('public')
                                ->downloadable()
                                ->openable()
                                ->maxFiles(1)
                                ->maxSize(4096)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->getUploadedFileNameForStorageUsing(
                                    fn (TemporaryUploadedFile $file, Get $get): string => ImageStorageNaming::filename(
                                        $file,
                                        'frontend-blog-posts',
                                        (string) $get('title'),
                                    )
                                )
                                ->helperText('Prefira imagens horizontais com boa luz e enquadramento.')
                                ->columnSpanFull(),
                            TextInput::make('cover_image_alt')
                                ->label('Descricao da imagem')
                                ->placeholder('Ex.: Equipe do CERAPE em atividade')
                                ->maxLength(255),
                            RichEditor::make('excerpt')
                                ->label('Resumo')
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
                                ->required()
                                ->placeholder('Escreva um resumo curto e convincente para a home e o blog.')
                                ->helperText('Selecione um trecho e escolha a cor do texto. Paleta sugerida: ' . implode(', ', FrontendTextColors::paletteExamples()) . '.')
                                ->textColors(FrontendTextColors::palette())
                                ->customTextColors()
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Conteudo completo')
                    ->description('Escreva o artigo em HTML rico usando o editor visual.')
                    ->icon('heroicon-o-pencil-square')
                    ->schema([
                        RichEditor::make('content')
                            ->label('Conteudo')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'textColor',
                                'bulletList',
                                'orderedList',
                                'blockquote',
                                'link',
                                'undo',
                                'redo',
                            ])
                            ->required()
                            ->placeholder('Desenvolva o artigo com introducao, informacoes principais e chamada final.')
                            ->helperText('O editor permite texto colorido. Use a paleta sugerida ou uma cor personalizada.')
                            ->textColors(FrontendTextColors::palette())
                            ->customTextColors()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

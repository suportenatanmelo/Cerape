<?php

namespace App\Filament\Resources\Homes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Support\FrontendTextColors;
use App\Support\ImageStorageNaming;

class HomeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Topo da pagina')
                    ->description('Configure a primeira area que o visitante ve: imagem, titulo, texto com links e botao de acao.')
                    ->icon('heroicon-o-photo')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            self::imageUpload('hero_image', 'Imagem principal', 'frontend/homes/hero', 'home-hero')
                                ->helperText('Use uma imagem horizontal com boa resolucao. Ela sera o fundo do topo da pagina.'),
                            TextInput::make('hero_image_alt')
                                ->label('Descricao da imagem')
                                ->placeholder('Ex.: Fachada ou equipe do CERAPE')
                                ->helperText('Texto usado por leitores de tela e acessibilidade.')
                                ->maxLength(255),
                            TextInput::make('title')
                                ->label('Titulo principal')
                                ->placeholder('Ex.: CERAPE')
                                ->helperText('Titulo grande exibido no topo da pagina.')
                                ->maxLength(255),
                            TextInput::make('cta_label')
                                ->label('Texto do botao')
                                ->placeholder('Ex.: Fale conosco')
                                ->helperText('Deixe vazio se nao quiser personalizar o texto do botao.')
                                ->maxLength(255),
                            TextInput::make('cta_url')
                                ->label('Link do botao')
                                ->placeholder('Ex.: #signup, #projects ou https://site.com')
                                ->helperText('Aceita links internos da pagina ou links externos.')
                                ->maxLength(255),
                            RichEditor::make('subtitle')
                                ->label('Texto de apoio')
                                ->toolbarButtons(self::textToolbar())
                                ->placeholder('Escreva o texto de apoio. Use o botao de link da barra para inserir links.')
                                ->helperText('Voce pode usar negrito, listas, links e cor de texto. Paleta sugerida: ' . implode(', ', FrontendTextColors::paletteExamples()) . '.')
                                ->textColors(FrontendTextColors::palette())
                                ->customTextColors()
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Destaques da Home')
                    ->description('Substitua os cards fixos por destaques editaveis com icone e texto profissional.')
                    ->icon('heroicon-o-star')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('feature_cards')
                            ->label('Cards de destaque')
                            ->addActionLabel('Adicionar destaque')
                            ->default(self::defaultFeatureCards())
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => filled($state['title'] ?? null) ? (string) $state['title'] : 'Destaque')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                ])->schema([
                                    TextInput::make('title')
                                        ->label('Titulo')
                                        ->placeholder('Ex.: Acolhimento Humanizado')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('icon')
                                        ->label('Icone')
                                        ->placeholder('heroicon-o-heart')
                                        ->helperText('Use um nome de icone do Heroicon para manter o visual elegante.')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('description')
                                        ->label('Descricao')
                                        ->placeholder('Ex.: Atendimento com empatia, respeito e dedicacao.')
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                            ])
                            ->columnSpanFull(),
                    ]),
                Section::make('Tratamentos da Home')
                    ->description('Organize os cards visuais que aparecem na secao de tratamentos da pagina inicial.')
                    ->icon('heroicon-o-photo')
                    ->columnSpanFull()
                    ->schema([
                        Repeater::make('treatment_cards')
                            ->label('Cards de tratamentos')
                            ->addActionLabel('Adicionar tratamento')
                            ->default(self::defaultTreatmentCards())
                            ->reorderable()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => filled($state['title'] ?? null) ? (string) $state['title'] : 'Tratamento')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                ])->schema([
                                    self::imageUpload('image', 'Imagem do card', 'frontend/homes/treatments', 'home-treatment'),
                                    TextInput::make('image_alt')
                                        ->label('Descricao da imagem')
                                        ->placeholder('Ex.: Imagem do tratamento')
                                        ->maxLength(255),
                                    TextInput::make('title')
                                        ->label('Titulo')
                                        ->placeholder('Ex.: Dependencia Quimica')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('description')
                                        ->label('Descricao')
                                        ->placeholder('Ex.: Tratamento completo com suporte, escuta e rotina.')
                                        ->required()
                                        ->columnSpanFull(),
                                ]),
                            ])
                            ->columnSpanFull(),
                    ]),
                Section::make('Secao sobre')
                    ->description('Apresente a instituicao, valores, servicos e links importantes.')
                    ->icon('heroicon-o-information-circle')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            self::imageUpload('about_image', 'Imagem da secao sobre', 'frontend/homes/about', 'home-about'),
                            TextInput::make('about_image_alt')
                                ->label('Descricao da imagem')
                                ->placeholder('Ex.: Imagem da secao sobre o CERAPE')
                                ->maxLength(255),
                            TextInput::make('about_title')
                                ->label('Titulo da secao')
                                ->placeholder('Ex.: Sobre o CERAPE')
                                ->maxLength(255),
                            RichEditor::make('about_subtitle')
                                ->label('Texto da secao')
                                ->toolbarButtons(self::textToolbar())
                                ->placeholder('Conte a historia, missao ou diferenciais. Links podem ser inseridos pela barra.')
                                ->helperText('Ideal para texto institucional com links uteis e cor de texto.')
                                ->textColors(FrontendTextColors::palette())
                                ->customTextColors()
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Secao projetos')
                    ->description('Configure o destaque visual e textual da area de projetos, servicos ou conteudos importantes.')
                    ->icon('heroicon-o-rectangle-stack')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            self::imageUpload('projects_image', 'Imagem da secao projetos', 'frontend/homes/projects', 'home-projects'),
                            TextInput::make('projects_image_alt')
                                ->label('Descricao da imagem')
                                ->placeholder('Ex.: Imagem representando projetos do CERAPE')
                                ->maxLength(255),
                            TextInput::make('projects_title')
                                ->label('Titulo da secao')
                                ->placeholder('Ex.: Projetos')
                                ->maxLength(255),
                            RichEditor::make('projects_subtitle')
                                ->label('Texto da secao')
                                ->toolbarButtons(self::textToolbar())
                                ->placeholder('Explique os projetos, acoes ou servicos. Voce tambem pode inserir links.')
                                ->helperText('Este texto aparece ao lado da imagem principal da secao e aceita cor de texto.')
                                ->textColors(FrontendTextColors::palette())
                                ->customTextColors()
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Chamada e formulario de contato')
                    ->description('Ajuste a chamada exibida acima do formulario publico de contato.')
                    ->icon('heroicon-o-envelope')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            self::imageUpload('signup_image', 'Imagem opcional da chamada', 'frontend/homes/signup', 'home-signup')
                                ->helperText('Opcional. Quando enviada, aparece acima do formulario de contato.'),
                            TextInput::make('signup_image_alt')
                                ->label('Descricao da imagem')
                                ->placeholder('Ex.: Imagem da secao de contato')
                                ->maxLength(255),
                            TextInput::make('signup_title')
                                ->label('Titulo da chamada')
                                ->placeholder('Ex.: Entre em contato')
                                ->maxLength(255),
                            RichEditor::make('signup_subtitle')
                                ->label('Texto de apoio')
                                ->toolbarButtons(self::textToolbar())
                                ->placeholder('Oriente o visitante antes do formulario. Links podem ser inseridos pela barra.')
                                ->helperText('O formulario publico ja coleta nome, e-mail, telefone, assunto e mensagem. Texto com cor personalizada tambem e permitido.')
                                ->textColors(FrontendTextColors::palette())
                                ->customTextColors()
                                ->columnSpanFull(),
                        ]),
                    ]),
            ]);
    }

    private static function imageUpload(string $name, string $label, string $directory, string $identifier): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->imageEditor()
            ->disk('public')
            ->directory(ImageStorageNaming::datedDirectory($directory))
            ->visibility('public')
            ->downloadable()
            ->openable()
            ->maxFiles(1)
            ->maxSize(4096)
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->getUploadedFileNameForStorageUsing(
                fn (TemporaryUploadedFile $file): string => ImageStorageNaming::filename(
                    $file,
                    Str::of($directory)->replace('/', '-')->value(),
                    $identifier,
                )
            );
    }

    /**
     * @return array<int, array<string, string>>
     */
    private static function defaultFeatureCards(): array
    {
        return [
            [
                'title' => 'Acolhimento Humanizado',
                'description' => 'Atendimento com empatia, respeito e dedicação.',
                'icon' => 'heroicon-o-heart',
            ],
            [
                'title' => 'Equipe Especializada',
                'description' => 'Profissionais capacitados e experientes.',
                'icon' => 'heroicon-o-users',
            ],
            [
                'title' => 'Tratamentos Personalizados',
                'description' => 'Planos terapêuticos individualizados.',
                'icon' => 'heroicon-o-sparkles',
            ],
            [
                'title' => 'Reintegração Social',
                'description' => 'Apoio para retornar à vida em sociedade.',
                'icon' => 'heroicon-o-arrow-top-right-on-square',
            ],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    private static function defaultTreatmentCards(): array
    {
        return [
            [
                'title' => 'Dependência Química',
                'description' => 'Tratamento completo com suporte, escuta e construção de rotina.',
                'image_alt' => 'Tratamento para dependência quimica',
                'image' => null,
            ],
            [
                'title' => 'Alcoolismo',
                'description' => 'Apoio especializado para superar e recomeçar.',
                'image_alt' => 'Tratamento para alcoolismo',
                'image' => null,
            ],
            [
                'title' => 'Terapia em Grupo',
                'description' => 'Fortalecimento emocional com atividades terapêuticas.',
                'image_alt' => 'Terapia em grupo',
                'image' => null,
            ],
            [
                'title' => 'Reintegração Social',
                'description' => 'Preparação gradual para retomar vínculos e autonomia.',
                'image_alt' => 'Reintegração social',
                'image' => null,
            ],
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function textToolbar(): array
    {
        return [
            'bold',
            'italic',
            'underline',
            'textColor',
            'bulletList',
            'orderedList',
            'link',
            'redo',
            'undo',
        ];
    }
}

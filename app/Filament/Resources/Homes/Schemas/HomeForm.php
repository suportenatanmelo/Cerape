<?php

namespace App\Filament\Resources\Homes\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

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
                            self::imageUpload('hero_image', 'Imagem principal', 'homes')
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
                                ->helperText('Voce pode usar negrito, listas e links.')
                                ->columnSpanFull(),
                        ]),
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
                            self::imageUpload('about_image', 'Imagem da secao sobre', 'homes/about'),
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
                                ->helperText('Ideal para texto institucional com links uteis.')
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
                            self::imageUpload('projects_image', 'Imagem da secao projetos', 'homes/projects'),
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
                                ->helperText('Este texto aparece ao lado da imagem principal da secao.')
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
                            self::imageUpload('signup_image', 'Imagem opcional da chamada', 'homes/signup')
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
                                ->helperText('O formulario publico ja coleta nome, e-mail, telefone, assunto e mensagem.')
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Carrossel opcional')
                    ->description('Ative quando quiser exibir varias imagens com textos e botoes na secao de projetos.')
                    ->icon('heroicon-o-queue-list')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('enable_carousel')
                            ->label('Exibir carrossel na secao de projetos')
                            ->helperText('Quando desligado, a pagina usa os blocos fixos do layout padrao.')
                            ->default(false),
                        Repeater::make('carousel_items')
                            ->label('Slides do carrossel')
                            ->addActionLabel('Adicionar slide')
                            ->collapsible()
                            ->reorderable()
                            ->defaultItems(0)
                            ->itemLabel(fn (array $state): ?string => filled($state['title'] ?? null) ? $state['title'] : 'Novo slide')
                            ->schema([
                                Grid::make([
                                    'default' => 1,
                                    'md' => 2,
                                ])->schema([
                                    self::imageUpload('image', 'Imagem do slide', 'homes/carousel')
                                        ->required()
                                        ->helperText('Obrigatoria para o slide aparecer no site. Prefira imagens horizontais.'),
                                    TextInput::make('alt')
                                        ->label('Descricao da imagem')
                                        ->placeholder('Ex.: Equipe em atendimento')
                                        ->maxLength(255),
                                    TextInput::make('title')
                                        ->label('Titulo do slide')
                                        ->placeholder('Ex.: Atendimento acolhedor')
                                        ->maxLength(255),
                                    TextInput::make('link_url')
                                        ->label('Link opcional do botao')
                                        ->placeholder('Ex.: #signup ou https://site.com')
                                        ->helperText('Preencha junto com o texto do botao para mostrar uma chamada clicavel.')
                                        ->maxLength(255),
                                    TextInput::make('link_label')
                                        ->label('Texto do botao')
                                        ->placeholder('Ex.: Saiba mais')
                                        ->maxLength(255),
                                    RichEditor::make('description')
                                        ->label('Descricao do slide')
                                        ->toolbarButtons(self::textToolbar())
                                        ->placeholder('Descreva o slide. Links tambem podem ser inseridos aqui.')
                                        ->helperText('A descricao aparece sobre a imagem em telas medias e grandes.')
                                        ->columnSpanFull(),
                                ]),
                            ])
                            ->helperText('Arraste os slides para mudar a ordem. Se o carrossel estiver desligado, estes slides ficam salvos, mas nao aparecem no site.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    private static function imageUpload(string $name, string $label, string $directory): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->imageEditor()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->downloadable()
            ->openable()
            ->maxFiles(1)
            ->maxSize(4096)
            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
            ->getUploadedFileNameForStorageUsing(
                fn (TemporaryUploadedFile $file): string => Str::uuid() . '.' . $file->getClientOriginalExtension()
            );
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
            'bulletList',
            'orderedList',
            'link',
            'redo',
            'undo',
        ];
    }
}

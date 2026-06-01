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
                Section::make('Topo da página')
                    ->description('Configure a primeira área que o visitante vê: imagem, título, texto com links e botão de ação.')
                    ->icon('heroicon-o-photo')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            self::imageUpload('hero_image', 'Imagem principal', 'homes')
                                ->helperText('Use uma imagem horizontal com boa resolução. Ela será o fundo do topo da página.'),
                            TextInput::make('hero_image_alt')
                                ->label('Descrição da imagem')
                                ->placeholder('Ex.: Fachada ou equipe do CERAPE')
                                ->helperText('Texto usado por leitores de tela e acessibilidade.')
                                ->maxLength(255),
                            TextInput::make('title')
                                ->label('Título principal')
                                ->placeholder('Ex.: CERAPE')
                                ->helperText('Título grande exibido no topo da página.')
                                ->maxLength(255),
                            TextInput::make('cta_label')
                                ->label('Texto do botão')
                                ->placeholder('Ex.: Fale conosco')
                                ->helperText('Deixe vazio se não quiser personalizar o texto do botão.')
                                ->maxLength(255),
                            TextInput::make('cta_url')
                                ->label('Link do botão')
                                ->placeholder('Ex.: #signup, #projects ou https://site.com')
                                ->helperText('Aceita links internos da página ou links externos.')
                                ->maxLength(255),
                            RichEditor::make('subtitle')
                                ->label('Texto de apoio')
                                ->toolbarButtons(self::textToolbar())
                                ->placeholder('Escreva o texto de apoio. Use o botão de link da barra para inserir links.')
                                ->helperText('Você pode usar negrito, listas e links.')
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Seção sobre')
                    ->description('Apresente a instituição, valores, serviços e links importantes.')
                    ->icon('heroicon-o-information-circle')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            self::imageUpload('about_image', 'Imagem da seção sobre', 'homes/about'),
                            TextInput::make('about_image_alt')
                                ->label('Descrição da imagem')
                                ->placeholder('Ex.: Imagem da seção sobre o CERAPE')
                                ->maxLength(255),
                            TextInput::make('about_title')
                                ->label('Título da seção')
                                ->placeholder('Ex.: Sobre o CERAPE')
                                ->maxLength(255),
                            RichEditor::make('about_subtitle')
                                ->label('Texto da seção')
                                ->toolbarButtons(self::textToolbar())
                                ->placeholder('Conte a historia, missao ou diferenciais. Links podem ser inseridos pela barra.')
                                ->helperText('Ideal para texto institucional com links uteis.')
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Seção projetos')
                    ->description('Configure o destaque visual e textual da área de projetos, serviços ou conteúdos importantes.')
                    ->icon('heroicon-o-rectangle-stack')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            self::imageUpload('projects_image', 'Imagem da seção projetos', 'homes/projects'),
                            TextInput::make('projects_image_alt')
                                ->label('Descrição da imagem')
                                ->placeholder('Ex.: Imagem representando projetos do CERAPE')
                                ->maxLength(255),
                            TextInput::make('projects_title')
                                ->label('Título da seção')
                                ->placeholder('Ex.: Projetos')
                                ->maxLength(255),
                            RichEditor::make('projects_subtitle')
                                ->label('Texto da seção')
                                ->toolbarButtons(self::textToolbar())
                                ->placeholder('Explique os projetos, acoes ou servicos. Voce tambem pode inserir links.')
                                ->helperText('Este texto aparece ao lado da imagem principal da seção.')
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Chamada e formulário de contato')
                    ->description('Ajuste a chamada exibida acima do formulário público de contato.')
                    ->icon('heroicon-o-envelope')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make([
                            'default' => 1,
                            'md' => 2,
                        ])->schema([
                            self::imageUpload('signup_image', 'Imagem opcional da chamada', 'homes/signup')
                                ->helperText('Opcional. Quando enviada, aparece acima do formulário de contato.'),
                            TextInput::make('signup_image_alt')
                                ->label('Descrição da imagem')
                                ->placeholder('Ex.: Imagem da seção de contato')
                                ->maxLength(255),
                            TextInput::make('signup_title')
                                ->label('Título da chamada')
                                ->placeholder('Ex.: Entre em contato')
                                ->maxLength(255),
                            RichEditor::make('signup_subtitle')
                                ->label('Texto de apoio')
                                ->toolbarButtons(self::textToolbar())
                                ->placeholder('Oriente o visitante antes do formulário. Links podem ser inseridos pela barra.')
                                ->helperText('O formulário público já coleta nome, e-mail, telefone, assunto e mensagem.')
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Carrossel opcional')
                    ->description('Ative quando quiser exibir várias imagens com textos e botões na seção de projetos.')
                    ->icon('heroicon-o-queue-list')
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('enable_carousel')
                            ->label('Exibir carrossel na seção de projetos')
                            ->helperText('Quando desligado, a página usa os blocos fixos do layout padrão.')
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
                                        ->helperText('Obrigatória para o slide aparecer no site. Prefira imagens horizontais.'),
                                    TextInput::make('alt')
                                        ->label('Descrição da imagem')
                                        ->placeholder('Ex.: Equipe em atendimento')
                                        ->maxLength(255),
                                    TextInput::make('title')
                                        ->label('Título do slide')
                                        ->placeholder('Ex.: Atendimento acolhedor')
                                        ->maxLength(255),
                                    TextInput::make('link_url')
                                        ->label('Link opcional do botão')
                                        ->placeholder('Ex.: #signup ou https://site.com')
                                        ->helperText('Preencha junto com o texto do botão para mostrar uma chamada clicável.')
                                        ->maxLength(255),
                                    TextInput::make('link_label')
                                        ->label('Texto do botão')
                                        ->placeholder('Ex.: Saiba mais')
                                        ->maxLength(255),
                                    RichEditor::make('description')
                                        ->label('Descrição do slide')
                                        ->toolbarButtons(self::textToolbar())
                                        ->placeholder('Descreva o slide. Links também podem ser inseridos aqui.')
                                        ->helperText('A descricao aparece sobre a imagem em telas medias e grandes.')
                                        ->columnSpanFull(),
                                ]),
                            ])
                            ->helperText('Arraste os slides para mudar a ordem. Se o carrossel estiver desligado, estes slides ficam salvos, mas não aparecem no site.')
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

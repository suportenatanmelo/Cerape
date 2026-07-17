<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Forms\BrandSettingsSchema;
use App\Models\FrontendSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FrontendSettingResource extends Resource
{
    protected static ?string $model = FrontendSetting::class;

    protected static string|UnitEnum|null $navigationGroup = 'Site público';

    protected static ?string $navigationLabel = 'Configurações do site';

    protected static ?string $modelLabel = 'configuração do site';

    protected static ?string $pluralModelLabel = 'configurações do site';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make('Configurações do site')
                ->tabs([
                    Tabs\Tab::make('Identidade')
                        ->schema([
                            \Filament\Forms\Components\Toggle::make('site_enabled')->label('Site ativo')->default(true),
                            \Filament\Forms\Components\Toggle::make('home_enabled')->label('Ativar home')->default(true),
                            \Filament\Forms\Components\TextInput::make('brand_name')->label('Nome da marca')->required()->default('Cerape'),
                            ...BrandSettingsSchema::components(),
                            \Filament\Forms\Components\TextInput::make('hero_title')->label('Título principal')->required()->default('Como funciona'),
                            \Filament\Forms\Components\TextInput::make('hero_subtitle')->label('Subtítulo')->default('Conteúdo editável pelo painel /frontend.'),
                            \Filament\Forms\Components\TextInput::make('hero_cta_label')->label('Texto do botão principal')->default('Agendar uma conversa'),
                            \Filament\Forms\Components\TextInput::make('hero_secondary_cta_label')->label('Texto do botão secundário')->default('Conhecer a jornada'),
                        ])->columns(2),
                    Tabs\Tab::make('Quem somos')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('menu_label_about')->label('Texto do menu')->default('Quem somos'),
                            \Filament\Forms\Components\TextInput::make('about_title')->label('Título da seção')->default('Sobre a CERAPE'),
                            \Filament\Forms\Components\Textarea::make('about_paragraph_one')
                                ->label('Primeiro parágrafo')
                                ->rows(4)
                                ->helperText('Você pode usar HTML simples, como <strong>texto em destaque</strong>.')
                                ->default('A CERAPE é uma casa de recuperação dedicada a oferecer acolhimento, tratamento e um novo começo para quem enfrenta a dependência química.'),
                            \Filament\Forms\Components\Textarea::make('about_paragraph_two')
                                ->label('Segundo parágrafo')
                                ->rows(4)
                                ->helperText('Você pode usar HTML simples, como <strong>texto em destaque</strong>.')
                                ->default('Acreditamos que a recuperação acontece em comunidade: por isso trabalhamos junto às famílias, com transparência e respeito ao tempo de cada pessoa, do primeiro dia até a reinserção social.'),
                            \Filament\Forms\Components\FileUpload::make('about_image_path')->label('Imagem da seção')->disk('public')->image()->directory(\App\Support\ImageStorageNaming::directory('galeria')),
                            \Filament\Forms\Components\TextInput::make('about_video_url')
                                ->label('Link do vídeo do YouTube')
                                ->placeholder('https://www.youtube.com/watch?v=...')
                                ->helperText('Cole o link do YouTube para exibir o vídeo na seção Quem somos.')
                                ->url(),
                            \Filament\Forms\Components\TextInput::make('about_video_width')
                                ->label('Largura do vídeo')
                                ->numeric()
                                ->default(560),
                            \Filament\Forms\Components\TextInput::make('about_video_height')
                                ->label('Altura do vídeo')
                                ->numeric()
                                ->default(315),
                        ])->columns(2),
                    Tabs\Tab::make('Jornada')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('journey_eyebrow')->label('Faixa superior')->default('Como funciona'),
                            \Filament\Forms\Components\TextInput::make('journey_title')->label('Título da seção')->default('Uma jornada em quatro etapas'),
                            \Filament\Forms\Components\Textarea::make('journey_description')->label('Descrição')->rows(3)->default('Cada fase tem objetivos claros, sempre com acompanhamento próximo da família e da equipe técnica.'),
                            \Filament\Forms\Components\TextInput::make('journey_empty_title_one')->label('Pilar 1')->default('Acolhimento'),
                            \Filament\Forms\Components\TextInput::make('journey_empty_title_two')->label('Pilar 2')->default('Estabilização'),
                            \Filament\Forms\Components\TextInput::make('journey_empty_title_three')->label('Pilar 3')->default('Fortalecimento'),
                            \Filament\Forms\Components\TextInput::make('journey_empty_title_four')->label('Pilar 4')->default('Reinserção'),
                            \Filament\Forms\Components\Textarea::make('journey_empty_description')->label('Texto vazio')->rows(2)->default('Cadastre os pilares no painel /frontend.'),
                        ])->columns(2),
                    Tabs\Tab::make('Equipe')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('team_eyebrow')->label('Faixa superior')->default('Equipe'),
                            \Filament\Forms\Components\TextInput::make('team_title')->label('Título da seção')->default('Quem cuida de você'),
                            \Filament\Forms\Components\Textarea::make('team_description')->label('Descrição')->rows(3)->default('Uma equipe multiprofissional acompanha cada etapa do tratamento, em conjunto e com plano individual para cada residente.'),
                            \Filament\Forms\Components\Textarea::make('team_empty_message')->label('Texto vazio')->rows(2)->default('Cadastre a equipe no painel /frontend.'),
                        ])->columns(2),
                    Tabs\Tab::make('Galeria')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('gallery_eyebrow')->label('Faixa superior')->default('Nosso espaço'),
                            \Filament\Forms\Components\TextInput::make('gallery_title')->label('Título da seção')->default('Galeria'),
                            \Filament\Forms\Components\Textarea::make('gallery_description')->label('Descrição')->rows(3)->default('Um ambiente pensado para acolher: áreas comuns, quartos, jardim e espaços terapêuticos.'),
                            \Filament\Forms\Components\TextInput::make('gallery_all_label')->label('Filtro todos')->default('Todos'),
                            \Filament\Forms\Components\Textarea::make('gallery_empty_message')->label('Texto vazio')->rows(2)->default('Cadastre as categorias da galeria no painel /frontend.'),
                        ])->columns(2),
                    Tabs\Tab::make('Blog')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('blog_eyebrow')->label('Faixa superior')->default('Conteúdo'),
                            \Filament\Forms\Components\TextInput::make('blog_title')->label('Título da seção')->default('Blog'),
                            \Filament\Forms\Components\Textarea::make('blog_description')->label('Descrição')->rows(3)->default('Artigos para famílias e pacientes sobre recuperação, saúde mental e reconstrução de vínculos.'),
                            \Filament\Forms\Components\Textarea::make('blog_empty_message')->label('Texto vazio')->rows(2)->default('Cadastre até 5 cards do blog no painel /frontend.'),
                        ])->columns(2),
                    Tabs\Tab::make('Contato')
                        ->schema([
                            \Filament\Forms\Components\TextInput::make('contact_section_eyebrow')->label('Faixa superior')->default('Atendimento confidencial'),
                            \Filament\Forms\Components\TextInput::make('contact_section_title')->label('Título da seção')->default('Vamos conversar'),
                            \Filament\Forms\Components\Textarea::make('contact_section_description')->label('Descrição da seção')->rows(3)->default('Toda mensagem é tratada com sigilo. Nossa equipe responde em até 24h.'),
                            \Filament\Forms\Components\TextInput::make('contact_whatsapp_cta_label')->label('CTA do WhatsApp')->default('Conversar no WhatsApp'),
                            \Filament\Forms\Components\Textarea::make('contact_whatsapp_title')->label('Texto de apoio do WhatsApp')->rows(2)->default('Fale com a nossa equipe agora pelo WhatsApp.'),
                            \Filament\Forms\Components\TextInput::make('contact_whatsapp_footer')->label('Rodapé do WhatsApp')->default('Atendimento 24h'),
                            \Filament\Forms\Components\TextInput::make('contact_phone_label')->label('Rótulo do telefone')->default('Telefone'),
                            \Filament\Forms\Components\TextInput::make('contact_phone_line')->label('Linha do telefone')->default('(11) 0000-0000 · WhatsApp 24h'),
                            \Filament\Forms\Components\TextInput::make('contact_address_label')->label('Rótulo do endereço')->default('Endereço'),
                            \Filament\Forms\Components\Textarea::make('contact_address_line')->label('Linha do endereço')->rows(2)->default('Rua das Acácias, 120 — Bairro Jardim, São Paulo/SP'),
                            \Filament\Forms\Components\TextInput::make('contact_email_label')->label('Rótulo do e-mail')->default('E-mail'),
                            \Filament\Forms\Components\TextInput::make('contact_email_line')->label('Linha do e-mail')->default('contato@cerape.com'),
                            \Filament\Forms\Components\TextInput::make('contact_form_name_placeholder')->label('Placeholder do nome')->default('Seu nome'),
                            \Filament\Forms\Components\TextInput::make('contact_form_phone_placeholder')->label('Placeholder do telefone')->default('(00) 00000-0000'),
                            \Filament\Forms\Components\TextInput::make('contact_form_email_placeholder')->label('Placeholder do e-mail')->default('seu@email.com'),
                            \Filament\Forms\Components\TextInput::make('contact_form_message_placeholder')->label('Placeholder da mensagem')->default('Como podemos ajudar?'),
                        ])->columns(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\TextColumn::make('brand_name')->label('Marca'),
            \Filament\Tables\Columns\IconColumn::make('site_enabled')->boolean()->label('Site'),
            \Filament\Tables\Columns\IconColumn::make('home_enabled')->boolean()->label('Home'),
        ])->recordActions([
            ActionGroup::make([
                Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Visualizar configuração')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fechar')
                    ->modalContent(fn ($record) => view('filament.frontend.record-preview', ['record' => $record])),
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Deletar'),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()?->email === 'suportenatanmelo@gmail.com';
    }
}

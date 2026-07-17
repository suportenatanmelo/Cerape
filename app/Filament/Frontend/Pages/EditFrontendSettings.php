<?php

namespace App\Filament\Frontend\Pages;

use App\Filament\Forms\BrandSettingsSchema;
use App\Models\FrontendSetting;
use App\Services\Branding\BrandSettingsService;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use UnitEnum;

class EditFrontendSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationLabel = 'Configuração do site';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $slug = 'configuracao-do-site';
    protected static ?string $title = 'Configuração do site';
    protected static string|UnitEnum|null $navigationGroup = 'Site público';
    protected static bool $shouldRegisterNavigation = true;

    protected string $view = 'filament.frontend.pages.edit-frontend-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = FrontendSetting::query()->first() ?? new FrontendSetting();
        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
                    Tabs::make('Configurações do site')
                ->tabs([
                    Tabs\Tab::make('Identidade')->schema([
                        \Filament\Forms\Components\Toggle::make('site_enabled')->label('Site ativo')->default(true),
                        \Filament\Forms\Components\Toggle::make('home_enabled')->label('Ativar home')->default(true),
                        \Filament\Forms\Components\TextInput::make('brand_name')->label('Nome da marca')->required()->default('Cerape'),
                        ...BrandSettingsSchema::components(),
                        \Filament\Forms\Components\TextInput::make('hero_title')->label('Título principal')->required()->default('Como funciona'),
                        \Filament\Forms\Components\TextInput::make('hero_subtitle')->label('Subtítulo')->default('Conteúdo editável pelo painel /frontend.'),
                        \Filament\Forms\Components\TextInput::make('hero_cta_label')->label('Texto do botão principal')->default('Agendar uma conversa'),
                        \Filament\Forms\Components\TextInput::make('hero_secondary_cta_label')->label('Texto do botão secundário')->default('Conhecer a jornada'),
                    ])->columns(2),
                    Tabs\Tab::make('Quem somos')->schema([
                        \Filament\Forms\Components\TextInput::make('menu_label_about')->label('Texto do menu')->default('Quem somos'),
                        \Filament\Forms\Components\TextInput::make('about_title')->label('Título da seção')->default('Sobre a CERAPE'),
                        \Filament\Forms\Components\Textarea::make('about_paragraph_one')->label('Primeiro parágrafo')->rows(4)->default('A CERAPE é uma casa de recuperação dedicada a oferecer acolhimento, tratamento e um novo começo para quem enfrenta a dependência química.'),
                        \Filament\Forms\Components\Textarea::make('about_paragraph_two')->label('Segundo parágrafo')->rows(4)->default('Acreditamos que a recuperação acontece em comunidade: por isso trabalhamos junto às famílias, com transparência e respeito ao tempo de cada pessoa, do primeiro dia até a reinserção social.'),
                        \Filament\Forms\Components\FileUpload::make('about_image_path')->label('Imagem da seção')->disk('public')->image()->directory(\App\Support\ImageStorageNaming::directory('galeria')),
                        \Filament\Forms\Components\TextInput::make('about_video_url')->label('Link do vídeo do YouTube')->placeholder('https://www.youtube.com/watch?v=...')->helperText('Cole o link do YouTube para exibir o vídeo na seção Quem somos.')->url(),
                        \Filament\Forms\Components\TextInput::make('about_video_width')->label('Largura do vídeo')->numeric()->default(560),
                        \Filament\Forms\Components\TextInput::make('about_video_height')->label('Altura do vídeo')->numeric()->default(315),
                    ])->columns(2),
                    Tabs\Tab::make('Jornada')->schema([
                        \Filament\Forms\Components\TextInput::make('journey_eyebrow')->label('Faixa superior')->default('Como funciona'),
                        \Filament\Forms\Components\TextInput::make('journey_title')->label('Título da seção')->default('Uma jornada em quatro etapas'),
                        \Filament\Forms\Components\Textarea::make('journey_description')->label('Descrição')->rows(3)->default('Cada fase tem objetivos claros, sempre com acompanhamento próximo da família e da equipe técnica.'),
                        \Filament\Forms\Components\TextInput::make('journey_empty_title_one')->label('Pilar 1')->default('Acolhimento'),
                        \Filament\Forms\Components\TextInput::make('journey_empty_title_two')->label('Pilar 2')->default('Estabilização'),
                        \Filament\Forms\Components\TextInput::make('journey_empty_title_three')->label('Pilar 3')->default('Fortalecimento'),
                        \Filament\Forms\Components\TextInput::make('journey_empty_title_four')->label('Pilar 4')->default('Reinserção'),
                        \Filament\Forms\Components\Textarea::make('journey_empty_description')->label('Texto vazio')->rows(2)->default('Cadastre os pilares no painel /frontend.'),
                    ])->columns(2),
                    Tabs\Tab::make('Equipe')->schema([
                        \Filament\Forms\Components\TextInput::make('team_eyebrow')->label('Faixa superior')->default('Equipe'),
                        \Filament\Forms\Components\TextInput::make('team_title')->label('Título da seção')->default('Quem cuida de você'),
                        \Filament\Forms\Components\Textarea::make('team_description')->label('Descrição')->rows(3)->default('Uma equipe multiprofissional acompanha cada etapa do tratamento, em conjunto e com plano individual para cada residente.'),
                        \Filament\Forms\Components\Textarea::make('team_empty_message')->label('Texto vazio')->rows(2)->default('Cadastre a equipe no painel /frontend.'),
                    ])->columns(2),
                    Tabs\Tab::make('Galeria')->schema([
                        \Filament\Forms\Components\TextInput::make('gallery_eyebrow')->label('Faixa superior')->default('Nosso espaço'),
                        \Filament\Forms\Components\TextInput::make('gallery_title')->label('Título da seção')->default('Galeria'),
                        \Filament\Forms\Components\Textarea::make('gallery_description')->label('Descrição')->rows(3)->default('Um ambiente pensado para acolher: áreas comuns, quartos, jardim e espaços terapêuticos.'),
                        \Filament\Forms\Components\TextInput::make('gallery_all_label')->label('Filtro todos')->default('Todos'),
                        \Filament\Forms\Components\Textarea::make('gallery_empty_message')->label('Texto vazio')->rows(2)->default('Cadastre as categorias da galeria no painel /frontend.'),
                    ])->columns(2),
                    Tabs\Tab::make('Blog')->schema([
                        \Filament\Forms\Components\TextInput::make('blog_eyebrow')->label('Faixa superior')->default('Conteúdo'),
                        \Filament\Forms\Components\TextInput::make('blog_title')->label('Título da seção')->default('Blog'),
                        \Filament\Forms\Components\Textarea::make('blog_description')->label('Descrição')->rows(3)->default('Artigos para famílias e pacientes sobre recuperação, saúde mental e reconstrução de vínculos.'),
                        \Filament\Forms\Components\Textarea::make('blog_empty_message')->label('Texto vazio')->rows(2)->default('Cadastre até 5 cards do blog no painel /frontend.'),
                    ])->columns(2),
                    Tabs\Tab::make('Contato')->schema([
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
        ])->statePath('data');
    }

    public function save(BrandSettingsService $brandSettingsService): void
    {
        $data = $this->form->getState();

        $brandSettingsService->update($data);

        Notification::make()->title('Configurações do site atualizadas')->success()->send();
    }

    public function getSubmitAction(): Action
    {
        return Action::make('save')
            ->label('Salvar')
            ->submit('save');
    }

    public function getAboutImageUrlProperty(): ?string
    {
        $settings = FrontendSetting::query()->first();

        $path = $settings?->about_image_path;

        return $path ? Storage::disk('public')->url($path) : null;
    }
}

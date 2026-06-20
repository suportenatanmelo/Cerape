<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\FrontendSettingResource\Pages\ManageFrontendSettings;
use App\Models\FrontendSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class FrontendSettingResource extends Resource
{
    protected static ?string $model = FrontendSetting::class;

    protected static string|UnitEnum|null $navigationGroup = 'Frontend';

    protected static ?string $navigationLabel = 'Configurações do site';

    protected static ?string $modelLabel = 'configuração do site';

    protected static ?string $pluralModelLabel = 'configurações do site';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Identidade e acesso')
                ->schema([
                    \Filament\Forms\Components\Toggle::make('site_enabled')->label('Site ativo')->default(true),
                    \Filament\Forms\Components\Toggle::make('home_enabled')->label('Ativar home')->default(true),
                    \Filament\Forms\Components\TextInput::make('brand_name')->label('Nome da marca')->required()->default('Cerape'),
                    \Filament\Forms\Components\TextInput::make('hero_title')->label('Título principal')->required()->default('Como funciona'),
                    \Filament\Forms\Components\TextInput::make('hero_subtitle')->label('Subtítulo')->default('Conteúdo editável pelo painel /frontend.'),
                ])->columns(2),
            \Filament\Schemas\Components\Section::make('Quem somos')
                ->schema([
                    \Filament\Forms\Components\TextInput::make('menu_label_about')->label('Texto do menu')->default('Quem somos'),
                    \Filament\Forms\Components\TextInput::make('about_title')->label('Título da seção')->default('Sobre a CERAPE'),
                    \Filament\Forms\Components\Textarea::make('about_paragraph_one')->label('Primeiro parágrafo')->rows(4)->default('A CERAPE é uma casa de recuperação dedicada a oferecer acolhimento, tratamento e um novo começo para quem enfrenta a dependência química.'),
                    \Filament\Forms\Components\Textarea::make('about_paragraph_two')->label('Segundo parágrafo')->rows(4)->default('Acreditamos que a recuperação acontece em comunidade: por isso trabalhamos junto às famílias, com transparência e respeito ao tempo de cada pessoa, do primeiro dia até a reinserção social.'),
                    \Filament\Forms\Components\FileUpload::make('about_image_path')->label('Imagem da seção')->disk('public')->image()->directory(\App\Support\ImageStorageNaming::directory('galeria')),
                ])->columns(2),
            \Filament\Schemas\Components\Section::make('Menu')
                ->schema([
                    \Filament\Forms\Components\TextInput::make('menu_label_home')->label('Menu Iniciar')->required()->default('Iniciar'),
                    \Filament\Forms\Components\TextInput::make('menu_label_pillars')->label('Menu Pilares')->required()->default('Pilares'),
                    \Filament\Forms\Components\TextInput::make('menu_label_team')->label('Menu Equipe')->required()->default('Equipe'),
                    \Filament\Forms\Components\TextInput::make('menu_label_gallery')->label('Menu Galeria')->required()->default('Galeria'),
                    \Filament\Forms\Components\TextInput::make('menu_label_blog')->label('Menu Blog')->required()->default('Blog'),
                    \Filament\Forms\Components\TextInput::make('menu_label_contact')->label('Menu Contato')->required()->default('Contato'),
                ])->columns(2),
            \Filament\Schemas\Components\Section::make('Cores')
                ->schema([
                    \Filament\Forms\Components\ColorPicker::make('header_primary_color')->label('Topo primário')->default('#0f172a'),
                    \Filament\Forms\Components\ColorPicker::make('header_secondary_color')->label('Topo secundário')->default('#155e75'),
                    \Filament\Forms\Components\ColorPicker::make('footer_primary_color')->label('Rodapé primário')->default('#111827'),
                    \Filament\Forms\Components\ColorPicker::make('footer_secondary_color')->label('Rodapé secundário')->default('#0f766e'),
                    \Filament\Forms\Components\ColorPicker::make('font_color')->label('Cor da fonte')->default('#e5e7eb'),
                    \Filament\Forms\Components\ColorPicker::make('accent_color')->label('Cor de destaque')->default('#38bdf8'),
                ])->columns(2),
            \Filament\Schemas\Components\Section::make('WhatsApp')
                ->schema([
                    \Filament\Forms\Components\TextInput::make('whatsapp_number')->label('Número')->default(''),
                    \Filament\Forms\Components\TextInput::make('whatsapp_message')->label('Mensagem padrão')->default('Olá, gostaria de mais informações.'),
                    \Filament\Forms\Components\TextInput::make('site_email')->label('E-mail')->default(''),
                ])->columns(1),
            \Filament\Schemas\Components\Section::make('Clínica e mapa')
                ->schema([
                    \Filament\Forms\Components\TextInput::make('clinic_name')->label('Nome da clínica')->default('Clínica CERAPE'),
                    \Filament\Forms\Components\Textarea::make('clinic_description')->label('Descrição')->rows(3)->default('Veja abaixo onde fica a clínica de recuperação e como chegar.'),
                    \Filament\Forms\Components\TextInput::make('clinic_address')->label('Endereço')->default(''),
                    \Filament\Forms\Components\TextInput::make('clinic_city')->label('Cidade')->default(''),
                    \Filament\Forms\Components\TextInput::make('clinic_state')->label('Estado')->default(''),
                    \Filament\Forms\Components\TextInput::make('clinic_zip_code')->label('CEP')->default(''),
                    \Filament\Forms\Components\TextInput::make('clinic_maps_link')->label('Link do Google Maps')->url()->default(''),
                    \Filament\Forms\Components\Textarea::make('clinic_google_maps_embed')
                        ->label('Código de incorporação do Google Maps')
                        ->rows(6)
                        ->helperText('Cole aqui o iframe completo do Google Maps ou apenas a URL do embed.')
                        ->default(''),
                ])->columns(2),
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
        return [
            'index' => ManageFrontendSettings::route('/'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()?->email === 'suportenatanmelo@gmail.com';
    }
}

<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\HeroSlideResource\Pages\ManageHeroSlides;
use App\Models\HeroSlide;
use App\Support\ImageStorageNaming;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use UnitEnum;

class HeroSlideResource extends Resource
{
    protected static ?string $model = HeroSlide::class;
    protected static string|UnitEnum|null $navigationGroup = 'Site público';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationLabel = 'Carrossel';
    protected static ?string $modelLabel = 'slide';
    protected static ?string $pluralModelLabel = 'slides';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Slide')
                ->description('Cadastre um Slide. Você pode alterar imagens Desktop e Mobile. As imagens serão exibidas automaticamente no topo do site.')
                ->schema([
                    TextInput::make('title')->label('Título')->required()->placeholder('Título principal do slide'),
                    TextInput::make('subtitle')->label('Subtítulo')->placeholder('Chamada curta acima do título'),
                    Textarea::make('description')->label('Texto')->rows(3)->columnSpanFull(),
                    FileUpload::make('image_path')->label('Imagem desktop')->disk('public')->image()->directory(ImageStorageNaming::directory('galeria')),
                    FileUpload::make('mobile_image_path')->label('Imagem mobile')->disk('public')->image()->directory(ImageStorageNaming::directory('galeria')),
                    TextInput::make('cta_label')->label('Botão 1')->placeholder('Agendar uma conversa'),
                    TextInput::make('cta_url')->label('URL botão 1')->placeholder('/contato'),
                    TextInput::make('secondary_cta_label')->label('Botão 2')->placeholder('Conhecer a jornada'),
                    TextInput::make('secondary_cta_url')->label('URL botão 2')->placeholder('#jornada'),
                ])->columns(2),

            Section::make('Aparência e comportamento')
                ->schema([
                    ColorPicker::make('text_color')->label('Cor do texto')->default('#ffffff'),
                    Select::make('alignment')
                        ->label('Alinhamento')
                        ->options([
                            'left' => 'Esquerda',
                            'center' => 'Centro',
                            'right' => 'Direita',
                        ])
                        ->default('left'),
                    ColorPicker::make('overlay_color')->label('Overlay')->default('#000000'),
                    TextInput::make('overlay_opacity')
                        ->label('Opacidade')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(100)
                        ->default(45)
                        ->helperText('Valor entre 0 e 100.'),
                    TextInput::make('position')
                        ->label('Ordem')
                        ->numeric()
                        ->default(fn (): int => static::nextSlidePosition()),
                    Toggle::make('show_buttons')->label('Exibir botões')->default(true),
                    Toggle::make('is_active')->label('Ativo')->default(true),
                ])->columns(4),

            Section::make('Agendamento')
                ->schema([
                    DateTimePicker::make('starts_at')->label('Data inicial'),
                    DateTimePicker::make('ends_at')->label('Data final'),
                ])->columns(2),
        ]);
    }

    protected static function nextSlidePosition(): int
    {
        return (int) (DB::table('hero_slides')->max('position') ?? 0) + 1;
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image_path')
                ->label('Imagem')
                ->getStateUsing(fn (HeroSlide $record): ?string => $record->imageUrl())
                ->size(64),
            TextColumn::make('title')->label('Título')->searchable(),
            TextColumn::make('subtitle')->label('Subtítulo'),
            TextColumn::make('position')->label('Ordem')->sortable(),
            TextColumn::make('starts_at')->label('Início')->dateTime('d/m/Y H:i')->placeholder('-'),
            TextColumn::make('ends_at')->label('Fim')->dateTime('d/m/Y H:i')->placeholder('-'),
            IconColumn::make('is_active')->boolean()->label('Ativo'),
        ])->recordActions([
            ActionGroup::make([
                Action::make('duplicar')
                    ->label('Duplicar')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (HeroSlide $record): void {
                        $copy = $record->replicate();
                        $copy->title = $record->title . ' (cópia)';
                        $copy->is_active = false;
                        $copy->position = static::nextSlidePosition();
                        $copy->save();
                    }),
                Action::make('visualizar')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Preview do slide')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fechar')
                    ->modalContent(fn ($record) => view('filament.frontend.record-preview', ['record' => $record])),
                EditAction::make()->label('Editar'),
                DeleteAction::make()->label('Excluir'),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageHeroSlides::route('/')];
    }
}

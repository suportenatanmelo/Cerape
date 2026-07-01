<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\HeroSlideResource\Pages\ManageHeroSlides;
use App\Models\HeroSlide;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
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
            \Filament\Schemas\Components\Section::make('Slide principal')
                ->schema([
                    \Filament\Forms\Components\TextInput::make('title')->label('Título')->required(),
                    \Filament\Forms\Components\TextInput::make('subtitle')->label('Subtítulo'),
                    \Filament\Forms\Components\Textarea::make('description')->label('Descrição')->rows(3),
                    \Filament\Forms\Components\FileUpload::make('image_path')->label('Imagem')->disk('public')->image()->directory(\App\Support\ImageStorageNaming::directory('galeria')),
                    \Filament\Forms\Components\TextInput::make('cta_label')->label('Texto do botão'),
                    \Filament\Forms\Components\TextInput::make('cta_url')
                        ->label('Link do botão')
                        ->default('/blog')
                        ->placeholder('Ex.: /blog/artigo-exemplo')
                        ->helperText('Use /blog para levar o visitante ao blog. A imagem do slide pode ser reaproveitada ou trocada depois no módulo Blog.'),
                    \Filament\Forms\Components\Toggle::make('show_buttons')
                        ->label('Exibir botões no carrossel')
                        ->default(true),
                    \Filament\Forms\Components\TextInput::make('position')
                        ->label('Ordem')
                        ->numeric()
                        ->default(fn (): int => static::nextSlidePosition()),
                    \Filament\Forms\Components\Toggle::make('is_active')->label('Ativo')->default(true),
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
            \Filament\Tables\Columns\ImageColumn::make('image_path')
                ->label('Imagem')
                ->getStateUsing(fn (HeroSlide $record): ?string => $record->imageUrl())
                ->size(64),
            \Filament\Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('subtitle')->label('Subtítulo'),
            \Filament\Tables\Columns\TextColumn::make('position')->label('Ordem')->sortable(),
            \Filament\Tables\Columns\IconColumn::make('is_active')->boolean()->label('Ativo'),
        ])->recordActions([
            ActionGroup::make([
                Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Visualizar slide')
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
        return ['index' => ManageHeroSlides::route('/')];
    }
}

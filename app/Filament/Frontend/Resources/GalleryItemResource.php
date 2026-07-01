<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\GalleryItemResource\Pages\ManageGalleryItems;
use App\Models\GalleryItem;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;
    protected static string|UnitEnum|null $navigationGroup = 'Site público';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Imagens da galeria';
    protected static ?string $modelLabel = 'imagem';
    protected static ?string $pluralModelLabel = 'imagens';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Imagens da galeria')->schema([
                Select::make('gallery_category_id')
                    ->label('Categoria')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('title')
                    ->label('Título base')
                    ->placeholder('Opcional. Se vazio, o nome do arquivo será usado.'),
                TextInput::make('caption')
                    ->label('Legenda')
                    ->placeholder('Opcional'),
                FileUpload::make('image_paths')
                    ->label('Imagens')
                    ->disk('public')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->openable()
                    ->downloadable()
                    ->directory(\App\Support\ImageStorageNaming::directory('galeria'))
                    ->required()
                    ->helperText('Selecione a categoria e envie uma ou várias imagens de uma vez.'),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\ImageColumn::make('image_path')
                ->label('Imagem')
                ->getStateUsing(fn (GalleryItem $record): ?string => $record->imageUrl())
                ->size(64),
            \Filament\Tables\Columns\TextColumn::make('category.name')->label('Categoria')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('title')->label('Titulo')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('caption')->label('Legenda')->limit(50),
            \Filament\Tables\Columns\IconColumn::make('active')->boolean()->label('Ativa'),
        ])->recordActions([
            ActionGroup::make([
                Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Visualizar imagem')
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
        return ['index' => ManageGalleryItems::route('/')];
    }
}

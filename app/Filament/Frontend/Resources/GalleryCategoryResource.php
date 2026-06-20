<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\GalleryCategoryResource\Pages\ManageGalleryCategories;
use App\Models\GalleryCategory;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class GalleryCategoryResource extends Resource
{
    protected static ?string $model = GalleryCategory::class;
    protected static string|UnitEnum|null $navigationGroup = 'Frontend';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Galeria';
    protected static ?string $modelLabel = 'categoria';
    protected static ?string $pluralModelLabel = 'categorias';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Categoria')->schema([
                TextInput::make('name')->label('Nome')->required(),
                TextInput::make('slug')->label('Slug')->required(),
                FileUpload::make('image_path')
                    ->label('Imagem da categoria')
                    ->disk('public')
                    ->image()
                    ->directory('imagens/galeria')
                    ->helperText('Essa imagem aparece como capa da categoria na pagina principal.'),
                TextInput::make('position')->label('Ordem')->numeric()->default(1),
                Toggle::make('show_on_home')->label('Mostrar no site principal')->default(true),
                Toggle::make('show_in_menu')->label('Mostrar no menu Galeria')->default(true),
                Toggle::make('active')->label('Ativa')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\ImageColumn::make('image_path')
                ->label('Imagem')
                ->getStateUsing(fn (GalleryCategory $record): ?string => $record->imageUrl())
                ->size(64),
            \Filament\Tables\Columns\TextColumn::make('name')->label('Categoria')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('slug')->label('Slug'),
            \Filament\Tables\Columns\TextColumn::make('position')->label('Ordem')->sortable(),
            \Filament\Tables\Columns\IconColumn::make('show_on_home')->boolean()->label('Home'),
            \Filament\Tables\Columns\IconColumn::make('show_in_menu')->boolean()->label('Menu'),
            \Filament\Tables\Columns\IconColumn::make('active')->boolean()->label('Ativa'),
        ])->recordActions([
            ActionGroup::make([
                Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Visualizar categoria')
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
        return ['index' => ManageGalleryCategories::route('/')];
    }
}

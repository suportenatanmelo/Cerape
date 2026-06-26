<?php

namespace App\Filament\Resources\ThemePalettes;

use App\Filament\Resources\ThemePalettes\Pages\ManageThemePalettes;
use App\Models\ThemePalette;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class ThemePaletteResource extends Resource
{
    protected static ?string $model = ThemePalette::class;
    protected static string|UnitEnum|null $navigationGroup = 'Administração e acesso';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationLabel = 'Paletas do sistema';
    protected static ?string $modelLabel = 'paleta';
    protected static ?string $pluralModelLabel = 'paletas';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Tema')
                ->schema([
                    \Filament\Forms\Components\TextInput::make('name')->label('Nome')->required(),
                    \Filament\Forms\Components\TextInput::make('position')->label('Ordem')->numeric()->default(1),
                    \Filament\Forms\Components\Toggle::make('is_active')->label('Ativa')->default(true),
                ])->columns(3),
            \Filament\Schemas\Components\Section::make('Cores do tema')
                ->schema([
                    \Filament\Forms\Components\ColorPicker::make('primary_color')->label('Primária')->required(),
                    \Filament\Forms\Components\ColorPicker::make('secondary_color')->label('Secundária')->required(),
                    \Filament\Forms\Components\ColorPicker::make('surface_color')->label('Superfície')->required(),
                    \Filament\Forms\Components\ColorPicker::make('background_color')->label('Fundo')->required(),
                    \Filament\Forms\Components\ColorPicker::make('text_color')->label('Texto')->required(),
                    \Filament\Forms\Components\ColorPicker::make('accent_color')->label('Destaque')->required(),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\TextColumn::make('name')->label('Tema')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('primary_color')->label('Primária'),
            \Filament\Tables\Columns\TextColumn::make('accent_color')->label('Destaque'),
            \Filament\Tables\Columns\IconColumn::make('is_active')->boolean()->label('Ativa'),
        ])->recordActions([
            ActionGroup::make([
                Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Visualizar paleta')
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
        return ['index' => ManageThemePalettes::route('/')];
    }
}

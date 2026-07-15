<?php

namespace App\Filament\Frontend\Resources\ThemePalettes;

use App\Filament\Frontend\Resources\ThemePalettes\Pages\ManageThemePalettes;
use App\Models\ThemePalette;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class ThemePaletteResource extends Resource
{
    protected static ?string $model = ThemePalette::class;
    protected static string|UnitEnum|null $navigationGroup = 'Site público';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationLabel = 'Temas';
    protected static ?string $modelLabel = 'tema';
    protected static ?string $pluralModelLabel = 'temas';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Tema')
                ->description('Escolha uma paleta ou crie novas cores. Para aplicar no site, clique em ATIVAR na listagem.')
                ->schema([
                    TextInput::make('name')->label('Nome')->required()->placeholder('Ex.: CERAPE Original'),
                    TextInput::make('position')->label('Ordem')->numeric()->default(1),
                    Toggle::make('is_active')->label('Disponível')->default(true),
                    Toggle::make('is_current')->label('Tema atual')->disabled(),
                ])->columns(4),

            Section::make('Cores principais')
                ->schema([
                    ColorPicker::make('primary_color')->label('Primary')->required(),
                    ColorPicker::make('secondary_color')->label('Secondary')->required(),
                    ColorPicker::make('accent_color')->label('Accent')->required(),
                    ColorPicker::make('success_color')->label('Success')->default('#16a34a'),
                    ColorPicker::make('warning_color')->label('Warning')->default('#f59e0b'),
                    ColorPicker::make('danger_color')->label('Danger')->default('#dc2626'),
                    ColorPicker::make('info_color')->label('Info')->default('#0284c7'),
                ])->columns(4),

            Section::make('Superfícies e interação')
                ->schema([
                    ColorPicker::make('background_color')->label('Background')->required(),
                    ColorPicker::make('surface_color')->label('Surface')->required(),
                    ColorPicker::make('text_color')->label('Texto')->required(),
                    ColorPicker::make('header_color')->label('Header')->default('#0f172a'),
                    ColorPicker::make('footer_color')->label('Footer')->default('#111827'),
                    ColorPicker::make('button_color')->label('Botões')->default('#0f766e'),
                    ColorPicker::make('link_color')->label('Links')->default('#0e7490'),
                    ColorPicker::make('card_color')->label('Cards')->default('#ffffff'),
                    ColorPicker::make('border_color')->label('Bordas')->default('#e5e7eb'),
                    ColorPicker::make('hover_color')->label('Hover')->default('#0f766e'),
                    ColorPicker::make('focus_color')->label('Focus')->default('#38bdf8'),
                    ColorPicker::make('dark_background_color')->label('Dark background')->default('#020617'),
                    ColorPicker::make('dark_surface_color')->label('Dark surface')->default('#0f172a'),
                ])->columns(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Tema')->searchable()->sortable(),
            TextColumn::make('primary_color')->label('Primary'),
            TextColumn::make('secondary_color')->label('Secondary'),
            TextColumn::make('accent_color')->label('Accent'),
            IconColumn::make('is_current')->boolean()->label('Atual'),
            IconColumn::make('is_active')->boolean()->label('Disponível'),
        ])->recordActions([
            ActionGroup::make([
                Action::make('ativar')
                    ->label('ATIVAR')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (ThemePalette $record): null => $record->activate()),
                Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Visualizar tema')
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
        return ['index' => ManageThemePalettes::route('/')];
    }
}

<?php

namespace App\Filament\Frontend\Resources;

use App\Filament\Frontend\Resources\PillarCardResource\Pages\ManagePillarCards;
use App\Models\PillarCard;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use UnitEnum;

class PillarCardResource extends Resource
{
    protected static ?string $model = PillarCard::class;
    protected static string|UnitEnum|null $navigationGroup = 'Site público';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationLabel = 'Pilares';
    protected static ?string $modelLabel = 'pilar';
    protected static ?string $pluralModelLabel = 'pilares';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Pilar')->schema([
                \Filament\Forms\Components\TextInput::make('title')->label('Título')->required(),
                \Filament\Forms\Components\Textarea::make('summary')->label('Texto sucinto')->required()->rows(3),
                \Filament\Forms\Components\TextInput::make('position')->label('Ordem')->numeric()->default(1),
                \Filament\Forms\Components\Toggle::make('active')->label('Ativo')->default(true),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            \Filament\Tables\Columns\TextColumn::make('title')->label('Título')->searchable(),
            \Filament\Tables\Columns\TextColumn::make('summary')->label('Resumo')->limit(60),
            \Filament\Tables\Columns\TextColumn::make('position')->label('Ordem')->sortable(),
            \Filament\Tables\Columns\IconColumn::make('active')->boolean()->label('Ativo'),
        ])->recordActions([
            ActionGroup::make([
                Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Visualizar pilar')
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
        return ['index' => ManagePillarCards::route('/')];
    }
}

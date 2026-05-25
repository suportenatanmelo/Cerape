<?php

namespace App\Filament\Resources\GeradorAtividades\Tables;

use App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GeradoresAtividadesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('data_programacao', 'desc')
            ->emptyStateHeading('Nenhuma programacao cadastrada')
            ->emptyStateDescription('As programacoes diarias de atividades aparecerao aqui.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->columns([
                TextColumn::make('titulo')
                    ->label('Titulo')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('data_programacao')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('acolhidos_ids')
                    ->label('Acolhidos')
                    ->formatStateUsing(fn (?array $state): string => GeradorAtividadeResource::formatAcolhidos($state, 3))
                    ->tooltip(fn ($record): string => GeradorAtividadeResource::formatAcolhidos($record->acolhidos_ids))
                    ->wrap(),
                TextColumn::make('atividades_matutinas')
                    ->label('Manha')
                    ->formatStateUsing(fn (?array $state): string => GeradorAtividadeResource::formatActivities($state, 2))
                    ->tooltip(fn ($record): string => GeradorAtividadeResource::formatActivities($record->atividades_matutinas))
                    ->wrap(),
                TextColumn::make('atividades_vespertinas')
                    ->label('Tarde')
                    ->formatStateUsing(fn (?array $state): string => GeradorAtividadeResource::formatActivities($state, 2))
                    ->tooltip(fn ($record): string => GeradorAtividadeResource::formatActivities($record->atividades_vespertinas))
                    ->wrap(),
                TextColumn::make('user.name')
                    ->label('Responsavel')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->label('Visualizar'),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }
}

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
            ->emptyStateHeading('Nenhum quadro semanal cadastrado')
            ->emptyStateDescription('Os planejamentos semanais de atividades aparecerão aqui.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list')
            ->columns([

                TextColumn::make('data_programacao')
                    ->label('Período')
                    ->formatStateUsing(fn ($record): string => GeradorAtividadeResource::getPeriodLabel($record))
                    ->sortable(),
                TextColumn::make('atividades_planejadas')
                    ->label('Atividades práticas')
                    ->formatStateUsing(fn ($record): string => GeradorAtividadeResource::formatPlannedActivities($record, 3))
                    ->tooltip(fn ($record): string => GeradorAtividadeResource::formatPlannedActivities($record))
                    ->wrap(),
                TextColumn::make('user.name')
                    ->label('Responsável')
                    ->placeholder('-')
                    ->toggleable(),
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

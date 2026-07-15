<?php

namespace App\Filament\Resources\Acolhidos\RelationManagers;

use App\Enums\SituacaoAcolhido;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;

class HistoricoSituacoesRelationManager extends RelationManager
{
    protected static string $relationship = 'historicoSituacoes';

    protected static ?string $recordTitleAttribute = 'created_at';

    private static function resolveSituacao(null|string|int|SituacaoAcolhido $state): ?SituacaoAcolhido
    {
        if (is_null($state)) {
            return null;
        }

        return $state instanceof SituacaoAcolhido ? $state : SituacaoAcolhido::from($state);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Data')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('usuario.name')
                    ->label('Usuário'),
                BadgeColumn::make('situacao_anterior')
                    ->label('Situação anterior')
                    ->formatStateUsing(fn($state): ?string => optional(self::resolveSituacao($state))->label())
                    ->colors(fn($state) => ($situacao = self::resolveSituacao($state)) ? [$situacao->color() => true] : []),
                BadgeColumn::make('situacao_nova')
                    ->label('Situação nova')
                    ->formatStateUsing(fn($state): ?string => optional(self::resolveSituacao($state))->label())
                    ->colors(fn($state) => ($situacao = self::resolveSituacao($state)) ? [$situacao->color() => true] : []),
                TextColumn::make('observacao')
                    ->label('Observação')
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([])
            ->defaultSort('created_at', 'desc');
    }
}

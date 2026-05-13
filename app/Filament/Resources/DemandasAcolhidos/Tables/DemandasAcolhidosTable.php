<?php

namespace App\Filament\Resources\DemandasAcolhidos\Tables;

use App\Filament\Resources\DemandasAcolhidos\DemandaAcolhidoResource;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DemandasAcolhidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('saida_prevista_em')
            ->columns([
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('demanda')
                    ->label('Demanda')
                    ->searchable()
                    ->wrap()
                    ->limit(60),
                TextColumn::make('saida_prevista_em')
                    ->label('Saida')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('retorno_previsto_em')
                    ->label('Chegada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('situacao')
                    ->label('Situacao')
                    ->badge()
                    ->color(fn ($record): string => self::statusColor($record?->saida_prevista_em, $record?->retorno_previsto_em))
                    ->state(fn ($record): string => self::statusLabel($record?->saida_prevista_em, $record?->retorno_previsto_em)),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->after(fn ($record) => DemandaAcolhidoResource::notifyUsers($record, 'updated')),
                DeleteAction::make()
                    ->after(fn ($record) => DemandaAcolhidoResource::notifyUsers($record, 'deleted')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    private static function statusLabel(mixed $saida, mixed $retorno): string
    {
        if (blank($saida) || blank($retorno)) {
            return 'Agenda incompleta';
        }

        try {
            $agora = now();
            $inicio = \Illuminate\Support\Carbon::parse($saida);
            $fim = \Illuminate\Support\Carbon::parse($retorno);
        } catch (\Throwable) {
            return 'Agenda invalida';
        }

        if ($agora->lt($inicio)) {
            return 'Agendada';
        }

        if ($agora->between($inicio, $fim)) {
            return 'Em andamento';
        }

        return 'Encerrada';
    }

    private static function statusColor(mixed $saida, mixed $retorno): string
    {
        return match (self::statusLabel($saida, $retorno)) {
            'Agendada' => 'info',
            'Em andamento' => 'warning',
            'Encerrada' => 'success',
            default => 'gray',
        };
    }
}

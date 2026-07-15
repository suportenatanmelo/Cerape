<?php

namespace App\Filament\Resources\Agendas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AgendasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('data', 'desc')
            ->emptyStateHeading('Nenhum agendamento disponivel')
            ->emptyStateDescription('Assim que um evento for cadastrado ele aparecera aqui e no calendario.')
            ->columns([
                TextColumn::make('data')
                    ->label('Data')
                    ->date()
                    ->sortable(),
                TextColumn::make('hora_inicio')
                    ->label('Horário')
                    ->sortable(),
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('funcionario.name')
                    ->label('Funcionário')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('tipo')
                    ->label('Tipo')
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
                ColorColumn::make('cor')
                    ->label('Cor'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'Agendado' => 'Agendado',
                        'Confirmado' => 'Confirmado',
                        'Em andamento' => 'Em andamento',
                        'Concluído' => 'Concluído',
                        'Cancelado' => 'Cancelado',
                        'Faltou' => 'Faltou',
                    ]),
                SelectFilter::make('tipo')
                    ->options([
                        'Consulta' => 'Consulta',
                        'Psicologia' => 'Psicologia',
                        'Assistência Social' => 'Assistência Social',
                        'Enfermagem' => 'Enfermagem',
                        'Reunião' => 'Reunião',
                        'Visita' => 'Visita',
                        'Outro' => 'Outro',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\Financeiro\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DiariaTrabalhoTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('data')->label('Data')->date()->sortable(),
            TextColumn::make('empresaParceira.nome')->label('Empresa')->searchable()->sortable(),
            TextColumn::make('acolhido.nome_completo_paciente')->label('Acolhido')->searchable()->sortable(),
            TextColumn::make('tipo_servico')->label('Serviço')->searchable(),
            TextColumn::make('valor_total')->label('Total')->money('BRL'),
            TextColumn::make('valor_liquido')
                ->label('Valor líquido')
                ->getStateUsing(fn ($record) => $record->valor_acolhido)
                ->money('BRL'),
            TextColumn::make('valor_desconto_logistico')
                ->label('Desconto logístico')
                ->getStateUsing(fn ($record) => $record->valor_cerape)
                ->money('BRL'),
            TextColumn::make('saldo_acolhido')
                ->label('Saldo do acolhido')
                ->getStateUsing(fn ($record) => $record->valor_acolhido)
                ->money('BRL'),
            TextColumn::make('situacao')->label('Situação')->badge(),
        ])->recordActions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }
}

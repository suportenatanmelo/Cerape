<?php

namespace App\Filament\Resources\Financeiro\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SaqueFinanceiroTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('data')->label('Data')->date()->sortable(),
            TextColumn::make('acolhido.nome_completo_paciente')->label('Acolhido')->searchable(),
            TextColumn::make('valor')->label('Valor')->money('BRL'),
            TextColumn::make('responsavel')->label('Responsável')->searchable(),
        ])->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}

<?php

namespace App\Filament\Resources\Financeiro\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EmpresaParceiraTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nome')->label('Nome')->searchable()->sortable(),
            TextColumn::make('responsavel')->label('Responsável')->searchable()->placeholder('-'),
            TextColumn::make('telefone')->label('Telefone')->placeholder('-'),
            IconColumn::make('ativo')->label('Ativo')->boolean(),
            TextColumn::make('updated_at')->label('Atualizado em')->dateTime()->sortable(),
        ])->recordActions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }
}

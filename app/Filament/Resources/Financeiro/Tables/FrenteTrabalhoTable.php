<?php

namespace App\Filament\Resources\Financeiro\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FrenteTrabalhoTable
{
    public static function configure(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('nome')->label('Nome')->searchable()->sortable(),
            TextColumn::make('descricao')->label('Descrição')->limit(80)->placeholder('-'),
            IconColumn::make('ativo')->label('Ativo')->boolean(),
        ])->recordActions([
            EditAction::make(),
            DeleteAction::make(),
        ]);
    }
}

<?php

namespace App\Filament\Resources\Acolhidos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AcolhidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Usuario responsavel')
                    ->searchable(),
                TextColumn::make('nome_completo_paciente')
                    ->label('Nome completo do paciente')
                    ->searchable(),
                TextColumn::make('data_nascimento')
                    ->label('Data de nascimento')
                    ->date()
                    ->sortable(),
                TextColumn::make('estado_civil')
                    ->label('Estado civil')
                    ->searchable(),
                TextColumn::make('municipio_do_paciente')
                    ->label('Municipio')
                    ->searchable(),
                TextColumn::make('numero_do_telefone')
                    ->label('Numero de telefone')
                    ->searchable()
                    ->placeholder('-'),
                IconColumn::make('tem_documentacao')
                    ->label('Tem documentacao?')
                    ->boolean(),
                IconColumn::make('trabalha')
                    ->label('Trabalha?')
                    ->boolean(),
                IconColumn::make('toma_medicamento')
                    ->label('Toma medicamento?')
                    ->boolean(),
                IconColumn::make('tem_filhos')
                    ->label('Tem filhos?')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

<?php

namespace App\Filament\Resources\Saudes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SaudesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('condicoes_saude')
                    ->label('Condicoes de saude')
                    ->formatStateUsing(function (mixed $state): string {
                        if (blank($state)) {
                            return '-';
                        }

                        if (is_array($state)) {
                            return implode(', ', array_filter($state));
                        }

                        return (string) $state;
                    })
                    ->wrap()
                    ->limit(80),
                IconColumn::make('faz_tratamento_medico')
                    ->label('Em tratamento')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

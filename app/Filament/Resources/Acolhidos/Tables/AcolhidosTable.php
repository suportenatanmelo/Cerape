<?php

namespace App\Filament\Resources\Acolhidos\Tables;

use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AcolhidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('Nenhum acolhido disponivel')
            ->emptyStateDescription('Assim que houver um acolhido vinculado com acesso liberado, ele aparecera aqui.')
            ->emptyStateIcon('heroicon-o-users')
            ->columns([
                TextColumn::make('id')
                    ->label('Matrícula')
                    ->searchable(),
                    ImageColumn::make('avatar')
                    ->disk('public')
                    ->label('Foto')
                    ->circular()
                    ->searchable(),
                TextColumn::make('nome_completo_paciente')
                    ->label('Nome do acolhido')
                    ->searchable(),
                TextColumn::make('data_nascimento')
                    ->label('Data de Nascimento')
                    ->searchable(),
                ToggleColumn::make('ativo')
                    ->label('Ativo')
                    ->sortable(),
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
                ActionGroup::make([
                    ViewAction::make()
                        ->label('Visualizar'),

                    EditAction::make()
                        ->after(
                            fn($record) =>
                            AcolhidoForm::notifyUsers($record, 'updated')
                        ),

                    DeleteAction::make()
                        ->after(
                            fn($record) =>
                            AcolhidoForm::notifyUsers($record, 'deleted')
                        ),
                ]),
            ])
            ->striped();
    }

}

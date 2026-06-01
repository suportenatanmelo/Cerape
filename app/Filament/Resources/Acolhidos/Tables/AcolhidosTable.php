<?php

namespace App\Filament\Resources\Acolhidos\Tables;

use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
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
                TextColumn::make('user.name')
                    ->label('Funcionário responsável')
                    ->searchable(),
                TextColumn::make('nome_completo_paciente')
                    ->label('Nome do paciente')
                    ->searchable(),
                IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('data_nascimento')
                    ->label('Data de nascimento')
                    ->date()
                    ->sortable(),
                TextColumn::make('estado_civil')
                    ->label('Estado civil')
                    ->searchable(),
                TextColumn::make('numero_do_telefone')
                    ->label('Numero de telefone')
                    ->searchable()
                    ->placeholder('-'),
                IconColumn::make('tem_documentacao')
                    ->label('Tem documentação?')
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

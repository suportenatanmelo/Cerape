<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProntuariosEvolucaoTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('data_prontuario', 'desc')
            ->emptyStateHeading('Nenhum prontuario encontrado')
            ->emptyStateDescription('Os prontuarios de evolucao do acolhido serao listados aqui.')
            ->emptyStateIcon('heroicon-o-document-text')
            ->columns([
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Registrado por')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('data_prontuario')
                    ->label('Data do prontuario')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('conteudo')
                    ->label('Resumo da evolucao')
                    ->formatStateUsing(fn (?string $state): string => Str::limit(trim(strip_tags($state ?? '')), 120))
                    ->wrap(),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
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
            ])
            ->striped();
    }
}

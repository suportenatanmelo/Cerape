<?php

namespace App\Filament\Resources\ProntuariosEvolucao\Tables;

use App\Filament\Resources\ProntuariosEvolucao\Schemas\ProntuarioEvolucaoForm;
use Filament\Actions\ActionGroup;
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
                    ->label('Responsável pela informação')
                    ->placeholder('-')
                    ->searchable(),
                TextColumn::make('funcao_responsavel_informacao')
                    ->label('Função')
                    ->badge()
                    ->color('success')
                    ->placeholder('-')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('nota_elogio')
                    ->label('Nota')
                    ->formatStateUsing(fn (mixed $state): string => filled($state) ? ProntuarioEvolucaoForm::renderPraiseRating($state) : '-')
                    ->html()
                    ->sortable()
                    ->placeholder('-'),
                TextColumn::make('atividade')
                    ->label('Atividade')
                    ->badge()
                    ->formatStateUsing(fn (mixed $state): string => ProntuarioEvolucaoForm::getClinicActivityLabel($state) ?? '-')
                    ->limit(50)
                    ->tooltip(fn ($record): ?string => ProntuarioEvolucaoForm::getClinicActivityLabel($record->atividade))
                    ->searchable(),
                TextColumn::make('data_prontuario')
                    ->label('Data do prontuario')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('proxima_data_prontuario')
                    ->label('Proxima data')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                      ViewAction::make()
                    ->label('Visualizar'),
                EditAction::make(),
                DeleteAction::make(),
                ]),

            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }
}

<?php

namespace App\Filament\Resources\AcolhidoGalerias\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AcolhidoGaleriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->contentGrid([
                'md' => 2,
                'xl' => 2,
            ])
            ->emptyStateHeading('Nenhuma galeria cadastrada')
            ->emptyStateDescription('Quando uma galeria for criada para um acolhido, ela aparecera aqui para manutencao da equipe.')
            ->emptyStateIcon('heroicon-o-photo')
            ->columns([
                ViewColumn::make('gallery_card')
                    ->label('')
                    ->view('filament.tables.columns.acolhido-galeria-card')
                    ->searchable(['titulo', 'descricao'])
                    ->sortable(false),
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('titulo')
                    ->label('Titulo')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('ativo')
                    ->label('Ativa')
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('data_adicao_imagem')
                    ->label('Data das imagens')
                    ->form([
                        DatePicker::make('data_inicial')
                            ->label('De'),
                        DatePicker::make('data_final')
                            ->label('Ate'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $startDate = $data['data_inicial'] ?? null;
                        $endDate = $data['data_final'] ?? null;

                        return $query->where(function (Builder $query) use ($startDate, $endDate): void {
                            $query->when(
                                filled($startDate) || filled($endDate),
                                function (Builder $query) use ($startDate, $endDate): void {
                                    $query->whereHas('media', function (Builder $mediaQuery) use ($startDate, $endDate): void {
                                        $mediaQuery
                                            ->when(
                                                filled($startDate),
                                                fn (Builder $mediaQuery): Builder => $mediaQuery->whereDate('created_at', '>=', $startDate),
                                            )
                                            ->when(
                                                filled($endDate),
                                                fn (Builder $mediaQuery): Builder => $mediaQuery->whereDate('created_at', '<=', $endDate),
                                            );
                                    })
                                        ->orWhere(function (Builder $legacyQuery) use ($startDate, $endDate): void {
                                            $legacyQuery
                                                ->whereNotNull('imagens')
                                                ->when(
                                                    filled($startDate),
                                                    fn (Builder $legacyQuery): Builder => $legacyQuery->whereDate('updated_at', '>=', $startDate),
                                                )
                                                ->when(
                                                    filled($endDate),
                                                    fn (Builder $legacyQuery): Builder => $legacyQuery->whereDate('updated_at', '<=', $endDate),
                                                );
                                        });
                                },
                            );
                        });
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Gerenciar imagens'),
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

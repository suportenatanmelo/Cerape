<?php

namespace App\Filament\Resources\AcolhidoGalerias\Tables;

use Alsaloul\ImageGallery\Tables\Columns\ImageGalleryColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
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
            ->emptyStateHeading('Nenhum album cadastrado')
            ->emptyStateDescription('Quando um album for criado para um acolhido, ele aparecera aqui para manutencao da equipe e exibicao no portal.')
            ->emptyStateIcon('heroicon-o-photo')
            ->columns([
                ImageGalleryColumn::make('gallery_preview')
                    ->label('Imagens')
                    ->state(fn ($record): array => $record->galleryUrls())
                    ->disk('public')
                    ->circular()
                    ->stacked(3)
                    ->ring(2, '#ffffff')
                    ->limit(4)
                    ->remainingTextBadge(true)
                    ->emptyText('Sem imagens'),
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record): ?string => $record->descricao ?: null)
                    ->wrap(),
                TextColumn::make('titulo')
                    ->label('Album')
                    ->searchable()
                    ->placeholder('Sem titulo')
                    ->sortable(),
                TextColumn::make('gallery_count')
                    ->label('Total de imagens')
                    ->badge()
                    ->state(fn ($record): string => (string) $record->galleryCount()),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable()
                    ->description(fn ($record): ?string => $record->lastGalleryUpdateLabel() ? 'Ultima imagem em ' . $record->lastGalleryUpdateLabel() : null),
                IconColumn::make('ativo')
                    ->label('Ativa')
                    ->boolean(),
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
                ViewAction::make()
                    ->label('Visualizar'),
                EditAction::make()
                    ->label('Gerenciar album'),
                DeleteAction::make(),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->label('Novo album'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }
}

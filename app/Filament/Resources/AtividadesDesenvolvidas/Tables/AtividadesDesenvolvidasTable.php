<?php

namespace App\Filament\Resources\AtividadesDesenvolvidas\Tables;

use App\Filament\Resources\AtividadesDesenvolvidas\AtividadeDesenvolvidaResource;
use App\Filament\Resources\AtividadesDesenvolvidas\Schemas\AtividadeDesenvolvidaForm;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AtividadesDesenvolvidasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('updated_at', 'desc')
            ->emptyStateHeading('Nenhuma atividade CRC registrada')
            ->emptyStateDescription('Os planos de atividades e acompanhamento CRC ficarao visiveis aqui.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check')
            ->columns([
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido')
                    ->placeholder('Nao vinculado')
                    ->searchable()
                    ->sortable(),
                IconColumn::make('atendimento_grupo_12_passos')
                    ->label('12 passos')
                    ->boolean(),
                TextColumn::make('atividades_esportivas')
                    ->label('Esportivas')
                    ->formatStateUsing(fn (mixed $state): string => self::formatList($state))
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('planejamento_saida')
                    ->label('Planejamento de saida')
                    ->formatStateUsing(fn (mixed $state): string => self::formatList($state))
                    ->limit(60)
                    ->wrap()
                    ->description(fn ($record): string => $record->observacoes_gerais ? \Illuminate\Support\Str::limit(strip_tags((string) $record->observacoes_gerais), 70) : 'Sem observacoes gerais'),
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
                EditAction::make()
                    ->after(fn ($record) => AtividadeDesenvolvidaResource::notifyUsers($record, 'updated')),
                DeleteAction::make()
                    ->after(fn ($record) => AtividadeDesenvolvidaResource::notifyUsers($record, 'deleted')),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped();
    }

    private static function formatList(mixed $state): string
    {
        if (blank($state)) {
            return '-';
        }

        if (is_array($state)) {
            $labels = AtividadeDesenvolvidaForm::allChecklistLabels();

            return implode(', ', array_map(
                fn (mixed $item): string => $labels[(string) $item] ?? (string) $item,
                array_filter($state),
            ));
        }

        return (string) $state;
    }
}

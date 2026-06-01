<?php

namespace App\Filament\Resources\AcolhidoVideos\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class AcolhidoVideosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('ordem')
            ->emptyStateHeading('Nenhum video cadastrado')
            ->emptyStateDescription('Os links do YouTube aprovados para a familia aparecerao aqui para manutencao.')
            ->emptyStateIcon('heroicon-o-play-circle')
            ->columns([
                ImageColumn::make('youtube_thumbnail')
                    ->label('Thumb')
                    ->getStateUsing(fn ($record): ?string => $record->youtubeThumbnailUrl())
                    ->square()
                    ->tooltip('Use Visualizar para assistir ao video'),
                TextColumn::make('acolhido.nome_completo_paciente')
                    ->label('Acolhido')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('titulo')
                    ->label('Titulo')
                    ->weight(FontWeight::Medium)
                    ->searchable()
                    ->limit(40),
                TextColumn::make('youtube_video_id')
                    ->label('Video ID')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('ordem')
                    ->label('Ordem')
                    ->sortable(),
                IconColumn::make('ativo')
                    ->label('Ativo')
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label('Atualizado em')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('visualizar')
                    ->label('Visualizar')
                    ->icon('heroicon-o-eye')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fechar')
                    ->modalWidth('4xl')
                    ->visible(fn ($record): bool => filled($record->youtubeEmbedUrl()))
                    ->modalHeading(fn ($record): string => $record->titulo ?: 'Visualizar video')
                    ->modalDescription(fn ($record): ?string => $record->descricao ?: 'Video liberado para o portal da familia.')
                    ->modalContent(function ($record): HtmlString {
                        $embedUrl = $record->youtubeEmbedUrl();

                        if (blank($embedUrl)) {
                            return new HtmlString('<p class="text-sm text-gray-500">Este video ainda nao possui um link valido para exibicao.</p>');
                        }

                        return new HtmlString(
                            '<div class="space-y-4">'.
                                '<div class="overflow-hidden rounded-lg border border-gray-200 bg-black shadow-sm">'.
                                    '<iframe src="'.e($embedUrl).'" class="aspect-video w-full" allowfullscreen loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>'.
                                '</div>'.
                            '</div>'
                        );
                    }),
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

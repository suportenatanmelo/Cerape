<?php

namespace App\Filament\Resources\AcolhidoVideos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                    ->square(),
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

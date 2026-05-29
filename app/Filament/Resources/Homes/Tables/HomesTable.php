<?php

namespace App\Filament\Resources\Homes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HomesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('hero_image')
                    ->label('Imagem')
                    ->disk('public')
                    ->height(80)
                    ->width(120),
                TextColumn::make('title')
                    ->label('Titulo')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('subtitle')
                    ->label('Legenda')
                    ->limit(60)
                    ->searchable()
                    ->placeholder('-'),
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
            ->defaultSort('updated_at', 'desc');
    }
}

<?php

namespace App\Filament\Frontend\Resources\Testimonials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagem')
                    ->disk('public')
                    ->height(64)
                    ->width(64),
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->weight('bold')
                    ->limit(28),
                TextColumn::make('role')
                    ->label('Perfil')
                    ->badge()
                    ->placeholder('-'),
                TextColumn::make('summary')
                    ->label('Resumo')
                    ->limit(48)
                    ->placeholder('-'),
                TextColumn::make('sort_order')
                    ->label('Ordem')
                    ->badge()
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Ativo'),
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
            ->defaultSort('sort_order', 'asc');
    }
}

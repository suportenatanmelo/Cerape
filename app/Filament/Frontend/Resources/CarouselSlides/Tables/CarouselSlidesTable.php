<?php

namespace App\Filament\Frontend\Resources\CarouselSlides\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CarouselSlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagem')
                    ->disk('public')
                    ->height(80)
                    ->width(120),
                TextColumn::make('eyebrow')
                    ->label('Etiqueta')
                    ->badge()
                    ->color('warning')
                    ->placeholder('-'),
                TextColumn::make('title')
                    ->label('Titulo')
                    ->searchable()
                    ->limit(45)
                    ->placeholder('-'),
                TextColumn::make('sort_order')
                    ->label('Ordem')
                    ->badge()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label('Ativo')
                    ->boolean(),
                TextColumn::make('cta_label')
                    ->label('Botao')
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
            ->defaultSort('sort_order', 'asc');
    }
}

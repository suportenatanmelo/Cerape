<?php

namespace App\Filament\Frontend\Resources\ContactPages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContactPagesTable
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
                    ->limit(45)
                    ->placeholder('-'),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->placeholder('-'),
                TextColumn::make('phone')
                    ->label('Telefone')
                    ->placeholder('-'),
                IconColumn::make('is_active')
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
            ->defaultSort('updated_at', 'desc');
    }
}

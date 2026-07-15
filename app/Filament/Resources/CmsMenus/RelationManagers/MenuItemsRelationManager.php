<?php

namespace App\Filament\Resources\CmsMenus\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MenuItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $recordTitleAttribute = 'title';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->placeholder('-'),
                BadgeColumn::make('active')
                    ->label('Ativo')
                    ->boolean(),
                TextColumn::make('position')
                    ->label('Posição')
                    ->sortable(),
            ]);
    }
}

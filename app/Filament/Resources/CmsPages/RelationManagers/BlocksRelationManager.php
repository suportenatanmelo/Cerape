<?php

namespace App\Filament\Resources\CmsPages\RelationManagers;

use App\Cms\Models\Block;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlocksRelationManager extends RelationManager
{
    protected static string $relationship = 'blocks';

    protected static ?string $recordTitleAttribute = 'type';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Tipo')
                    ->searchable(),
                BadgeColumn::make('active')
                    ->label('Ativo')
                    ->boolean(),
                TextColumn::make('position')
                    ->label('Posição')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Criado em')
                    ->dateTime(),
            ]);
    }
}

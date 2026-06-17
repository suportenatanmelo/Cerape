<?php

namespace App\Filament\Frontend\Resources\ThemeProfiles\Tables;

use App\Models\FrontendThemeProfile;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class ThemeProfilesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Perfil')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('preset_key')
                    ->label('Base')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ?: 'Personalizado'),
                TextColumn::make('body_font')
                    ->label('Fonte do texto')
                    ->badge(),
                TextColumn::make('display_font')
                    ->label('Fonte dos titulos')
                    ->badge(),
                TextColumn::make('background_color')
                    ->label('Fundo')
                    ->badge()
                    ->placeholder('-'),
                TextColumn::make('text_color')
                    ->label('Texto')
                    ->badge()
                    ->placeholder('-'),
                ToggleColumn::make('is_active')
                    ->label('Ativo')
                    ->sortable(),
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

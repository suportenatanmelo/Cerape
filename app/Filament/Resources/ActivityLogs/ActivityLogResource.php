<?php

namespace App\Filament\Resources\ActivityLogs;

use App\Filament\Resources\ActivityLogs\Pages\ListActivityLogs;
use App\Models\ActivityLog;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ActivityLogs\Pages\ViewActivityLog;

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';

    protected static string|\UnitEnum|null $navigationGroup = 'Controle de acesso e auditoria';

    protected static ?string $navigationLabel = 'Auditoria';

    protected static ?string $modelLabel = 'Registro de auditoria';

    protected static ?string $pluralModelLabel = 'Auditoria';

    protected static ?int $navigationSort = 101;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('executed_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('executed_at')
                    ->label('Data/Hora')
                    ->dateTime('d/m/Y H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Usuário')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('module')
                    ->label('Módulo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('action')
                    ->label('Ação')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(90)
                    ->wrap(),
                Tables\Columns\TextColumn::make('model_type')
                    ->label('Modelo')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(60),
                Tables\Columns\TextColumn::make('model_id')
                    ->label('Registro')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
                Tables\Columns\TextColumn::make('method')
                    ->label('Método')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ip')
                    ->label('IP')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('browser')
                    ->label('Navegador')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(40),
                Tables\Columns\TextColumn::make('platform')
                    ->label('Plataforma')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('device')
                    ->label('Dispositivo')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->limit(40),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('module')
                    ->label('Módulo')
                    ->options(config('activity-logs.modules', [])),
                Tables\Filters\SelectFilter::make('action')
                    ->label('Ação')
                    ->options([
                        'create' => 'create',
                        'update' => 'update',
                        'delete' => 'delete',
                        'restore' => 'restore',
                        'force_delete' => 'force_delete',
                        'login' => 'login',
                        'logout' => 'logout',
                        'failed_login' => 'failed_login',
                        'view' => 'view',
                        'download' => 'download',
                        'upload' => 'upload',
                    ]),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('Usuário')
                    ->relationship('user', 'name'),
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
            'view' => ViewActivityLog::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()?->hasRole(config('filament-shield.super_admin.name', 'super_admin'));
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('user');
    }
}
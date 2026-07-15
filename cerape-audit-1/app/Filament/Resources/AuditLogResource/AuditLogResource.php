<?php

namespace App\Filament\Resources\AuditLogResource;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationGroup = 'Segurança';

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static string $slug = 'audit-logs';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                // Define the fields to display in the resource form if needed
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Data')->dateTime(),
                Tables\Columns\TextColumn::make('user.name')->label('Usuário'),
                Tables\Columns\TextColumn::make('event')->label('Evento'),
                Tables\Columns\TextColumn::make('module')->label('Módulo'),
                Tables\Columns\TextColumn::make('description')->label('Descrição'),
                Tables\Columns\TextColumn::make('ip_address')->label('IP'),
                Tables\Columns\TextColumn::make('user_agent')->label('Browser'),
                Tables\Columns\TextColumn::make('platform')->label('Sistema'),
                Tables\Columns\TextColumn::make('model')->label('Modelo'),
                Tables\Columns\TextColumn::make('model_id')->label('Registro'),
            ])
            ->filters([
                // Define filters for the table
            ])
            ->actions([
                // Define actions for the table
            ])
            ->bulkActions([
                // Define bulk actions for the table
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
            'view' => Pages\ViewAuditLog::route('/{record}'),
        ];
    }
}
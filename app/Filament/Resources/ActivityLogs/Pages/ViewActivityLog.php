<?php

namespace App\Filament\Resources\ActivityLogs\Pages;

use App\Filament\Resources\ActivityLogs\ActivityLogResource;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ViewActivityLog extends ViewRecord
{
    protected static string $resource = ActivityLogResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detalhes da ação')
                    ->schema([
                        TextEntry::make('executed_at')->label('Data')->dateTime('d/m/Y H:i'),
                        TextEntry::make('module')->label('Módulo'),
                        TextEntry::make('action')->label('Ação'),
                        TextEntry::make('description')->label('Descrição'),
                        TextEntry::make('method')->label('Método'),
                        TextEntry::make('url')->label('URL')->url(),
                    ]),
                Section::make('Usuário e contexto')
                    ->schema([
                        TextEntry::make('user.name')->label('Usuário'),
                        TextEntry::make('ip')->label('IP'),
                        TextEntry::make('browser')->label('Navegador'),
                        TextEntry::make('session_id')->label('Sessão'),
                    ]),
                Section::make('Valores')
                    ->schema([
                        TextEntry::make('old_values')->label('Antes')->state(fn ($record) => (array) ($record->old_values ?? [])),
                        TextEntry::make('new_values')->label('Depois')->state(fn ($record) => (array) ($record->new_values ?? [])),
                    ]),
            ]);
    }
}

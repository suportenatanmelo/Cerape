<?php

namespace App\Filament\Resources\AuditLogResource\Pages;

use App\Filament\Resources\AuditLogResource;
use Filament\Pages\ListRecords;

class ListAuditLogs extends ListRecords
{
    protected static string $resource = AuditLogResource::class;

    protected function getTableColumns(): array
    {
        return [
            // Define the columns to display in the audit log table
            'created_at' => 'Data',
            'user_id' => 'Usuário',
            'event' => 'Evento',
            'module' => 'Módulo',
            'description' => 'Descrição',
            'ip_address' => 'IP',
            'user_agent' => 'Browser',
            'platform' => 'Sistema',
            'model' => 'Modelo',
            'model_id' => 'Registro',
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            // Define the filters for the audit log table
            'today' => 'Hoje',
            'yesterday' => 'Ontem',
            'this_week' => 'Esta semana',
            'this_month' => 'Este mês',
            'user' => 'Usuário',
            'event' => 'Evento',
            'module' => 'Módulo',
            'model' => 'Modelo',
        ];
    }

    protected function getTableSearchableAttributes(): array
    {
        return [
            'user_id',
            'ip_address',
            'description',
            'model',
            'event',
        ];
    }
}
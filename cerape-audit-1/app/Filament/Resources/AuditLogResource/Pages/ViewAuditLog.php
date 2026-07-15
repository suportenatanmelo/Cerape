<?php

namespace App\Filament\Resources\AuditLogResource\Pages;

use App\Filament\Resources\AuditLogResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAuditLog extends ViewRecord
{
    protected static string $resource = AuditLogResource::class;

    protected function getActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label(__('Voltar'))
                ->url(route('filament.resources.audit-logs.index')),
        ];
    }

    protected function getHeader(): string
    {
        return __('Detalhes do Log de Auditoria');
    }

    protected function getRecordTitle(): string
    {
        return __('Log de Auditoria: :id', ['id' => $this->record->id]);
    }

    protected function getRecord(): mixed
    {
        return $this->record;
    }
}
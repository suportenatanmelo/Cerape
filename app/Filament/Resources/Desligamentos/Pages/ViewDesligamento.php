<?php

namespace App\Filament\Resources\Desligamentos\Pages;

use App\Filament\Resources\Desligamentos\DesligamentoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDesligamento extends ViewRecord
{
    protected static string $resource = DesligamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}

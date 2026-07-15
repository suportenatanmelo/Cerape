<?php

namespace App\Filament\Resources\Desligamentos\Pages;

use App\Filament\Resources\Desligamentos\DesligamentoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDesligamento extends EditRecord
{
    protected static string $resource = DesligamentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

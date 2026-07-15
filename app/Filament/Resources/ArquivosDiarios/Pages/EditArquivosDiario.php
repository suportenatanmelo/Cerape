<?php

namespace App\Filament\Resources\ArquivosDiarios\Pages;

use App\Filament\Resources\ArquivosDiarios\ArquivosDiarioResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditArquivosDiario extends EditRecord
{
    protected static string $resource = ArquivosDiarioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}

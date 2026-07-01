<?php

namespace App\Filament\Frontend\Resources\CmsContentResource\Pages;

use App\Filament\Frontend\Resources\CmsContentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCmsContents extends ManageRecords
{
    protected static string $resource = CmsContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Cadastrar conteúdo'),
        ];
    }
}

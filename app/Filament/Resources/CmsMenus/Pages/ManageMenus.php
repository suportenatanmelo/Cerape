<?php

namespace App\Filament\Resources\CmsMenus\Pages;

use App\Filament\Resources\CmsMenus\MenuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMenus extends ManageRecords
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo menu'),
        ];
    }
}

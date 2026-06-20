<?php

namespace App\Filament\Frontend\Resources\FrontendSettingResource\Pages;

use App\Filament\Frontend\Resources\FrontendSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFrontendSettings extends ManageRecords
{
    protected static string $resource = FrontendSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

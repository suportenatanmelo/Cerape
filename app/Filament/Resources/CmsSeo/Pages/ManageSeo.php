<?php

namespace App\Filament\Resources\CmsSeo\Pages;

use App\Filament\Resources\CmsSeo\SeoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSeo extends ManageRecords
{
    protected static string $resource = SeoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova meta'),
        ];
    }
}

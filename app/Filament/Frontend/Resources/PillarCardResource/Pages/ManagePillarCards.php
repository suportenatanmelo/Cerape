<?php

namespace App\Filament\Frontend\Resources\PillarCardResource\Pages;

use App\Filament\Frontend\Resources\PillarCardResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePillarCards extends ManageRecords
{
    protected static string $resource = PillarCardResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}

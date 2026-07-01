<?php

namespace App\Filament\Frontend\Resources\GalleryCategoryResource\Pages;

use App\Filament\Frontend\Resources\GalleryCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageGalleryCategories extends ManageRecords
{
    protected static string $resource = GalleryCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}

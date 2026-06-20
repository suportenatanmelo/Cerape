<?php

namespace App\Filament\Frontend\Resources\HeroSlideResource\Pages;

use App\Filament\Frontend\Resources\HeroSlideResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageHeroSlides extends ManageRecords
{
    protected static string $resource = HeroSlideResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}

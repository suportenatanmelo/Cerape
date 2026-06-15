<?php

namespace App\Filament\Frontend\Resources\CarouselSlides\Pages;

use App\Filament\Frontend\Resources\CarouselSlides\CarouselSlideResource;
use App\Support\FrontendSchema;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCarouselSlides extends ManageRecords
{
    protected static string $resource = CarouselSlideResource::class;

    public function mount(): void
    {
        FrontendSchema::ensureCarouselSlidesTableExists();

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo slide'),
        ];
    }

    protected function configureEditAction(EditAction $action): void
    {
        $action->label('Editar slide');
    }
}

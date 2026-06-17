<?php

namespace App\Filament\Frontend\Resources\Testimonials\Pages;

use App\Filament\Frontend\Resources\Testimonials\TestimonialResource;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTestimonials extends ManageRecords
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo depoimento'),
        ];
    }

    protected function configureEditAction(EditAction $action): void
    {
        $action->label('Editar depoimento');
    }
}

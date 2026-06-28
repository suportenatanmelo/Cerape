<?php

namespace App\Filament\Resources\AcolhidoVideos\Pages;

use App\Filament\Resources\AcolhidoVideos\AcolhidoVideoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAcolhidoVideos extends ManageRecords
{
    protected static string $resource = AcolhidoVideoResource::class;

    public function getTitle(): string
    {
        return 'Videos do YouTube';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Novo video'),
        ];
    }
}

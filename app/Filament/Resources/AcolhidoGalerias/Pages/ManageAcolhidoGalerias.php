<?php

namespace App\Filament\Resources\AcolhidoGalerias\Pages;

use App\Filament\Resources\AcolhidoGalerias\AcolhidoGaleriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAcolhidoGalerias extends ManageRecords
{
    protected static string $resource = AcolhidoGaleriaResource::class;

    public function getTitle(): string
    {
        return 'Galeria de imagens';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova galeria'),
        ];
    }
}

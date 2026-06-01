<?php

namespace App\Filament\Resources\AcolhidoGalerias\Pages;

use App\Filament\Resources\AcolhidoGalerias\AcolhidoGaleriaResource;
use Filament\Resources\Pages\ManageRecords;

class ManageAcolhidoGalerias extends ManageRecords
{
    protected static string $resource = AcolhidoGaleriaResource::class;

    public function getTitle(): string
    {
        return 'Álbuns de imagens';
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

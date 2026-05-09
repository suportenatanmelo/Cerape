<?php

namespace App\Filament\Resources\SubstanciaPsicoativas\Pages;

use App\Filament\Resources\SubstanciaPsicoativas\SubstanciaPsicoativaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSubstanciaPsicoativas extends ManageRecords
{
    protected static string $resource = SubstanciaPsicoativaResource::class;

    public function getTitle(): string
    {
        return 'Substancias psicoativas';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nova substancia psicoativa'),
        ];
    }
}

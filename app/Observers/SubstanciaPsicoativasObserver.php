<?php

namespace App\Observers;

use App\Filament\Resources\SubstanciaPsicoativas\SubstanciaPsicoativaResource;
use App\Models\SubstanciaPsicoativas;

class SubstanciaPsicoativasObserver
{
    public function created(SubstanciaPsicoativas $substanciaPsicoativa): void
    {
        SubstanciaPsicoativaResource::notifyUsers($substanciaPsicoativa, 'created');
    }

    public function updated(SubstanciaPsicoativas $substanciaPsicoativa): void
    {
        SubstanciaPsicoativaResource::notifyUsers($substanciaPsicoativa, 'updated');
    }

    public function deleted(SubstanciaPsicoativas $substanciaPsicoativa): void
    {
        SubstanciaPsicoativaResource::notifyUsers($substanciaPsicoativa, 'deleted');
    }
}

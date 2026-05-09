<?php

namespace App\Observers;

use App\Filament\Resources\Acolhidos\Schemas\AcolhidoForm;
use App\Models\Acolhido;

class AcolhidoObserver
{
    /**
     * Handle the Acolhido "created" event.
     */
    public function created(Acolhido $acolhido): void
    {
        AcolhidoForm::notifyUsers($acolhido, 'created');
    }

    /**
     * Handle the Acolhido "updated" event.
     */
    public function updated(Acolhido $acolhido): void
    {
        AcolhidoForm::notifyUsers($acolhido, 'updated');
    }

    /**
     * Handle the Acolhido "deleted" event.
     */
    public function deleted(Acolhido $acolhido): void
    {
        AcolhidoForm::notifyUsers($acolhido, 'deleted');
    }

    /**
     * Handle the Acolhido "restored" event.
     */
    public function restored(Acolhido $acolhido): void
    {
        AcolhidoForm::notifyUsers($acolhido, 'restored');
    }

    /**
     * Handle the Acolhido "force deleted" event.
     */
    public function forceDeleted(Acolhido $acolhido): void
    {
        AcolhidoForm::notifyUsers($acolhido, 'forceDeleted');
    }
}

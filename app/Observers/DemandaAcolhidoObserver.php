<?php

namespace App\Observers;

use App\Models\DemandaAcolhido;
use App\Models\User;
use Filament\Notifications\Notification;

class DemandaAcolhidoObserver
{
    /**
     * Handle the DemandaAcolhido "created" event.
     */
    public function created(DemandaAcolhido $demandaAcolhido): void
    {
        $user = auth()->user();

        Notification::make()
            ->title('Demanda criada com sucesso')
            ->body("Uma demanda do acolhido {$user->nome_completo} acaba de ser criada")
            ->sendToDatabase(User::all());
    }

    /**
     * Handle the DemandaAcolhido "updated" event.
     */
    public function updated(DemandaAcolhido $demandaAcolhido): void
    {
        Notification::make()
            ->title('Demanda atualizada com sucesso')
            ->body('Uma demanda do acholhido $user->nome_completo acaba de ser criada')
            ->sendToDatabase(User::all());
    }

    /**
     * Handle the DemandaAcolhido "deleted" event.
     */
    public function deleted(DemandaAcolhido $demandaAcolhido): void
    {
        //
    }

    /**
     * Handle the DemandaAcolhido "restored" event.
     */
    public function restored(DemandaAcolhido $demandaAcolhido): void
    {
        //
    }

    /**
     * Handle the DemandaAcolhido "force deleted" event.
     */
    public function forceDeleted(DemandaAcolhido $demandaAcolhido): void
    {
        //
    }
}

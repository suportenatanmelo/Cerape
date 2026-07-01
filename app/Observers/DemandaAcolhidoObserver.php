<?php

namespace App\Observers;

use App\Services\Agenda\AgendaService;
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
        app(AgendaService::class)->syncAgendaFromDemanda($demandaAcolhido);

        $user = auth()->user();

        Notification::make()
            ->title('Demanda criada com sucesso')
            ->body('A demanda do acolhido foi integrada ao calendário da agenda.')
            ->sendToDatabase(User::all());
    }

    /**
     * Handle the DemandaAcolhido "updated" event.
     */
    public function updated(DemandaAcolhido $demandaAcolhido): void
    {
        app(AgendaService::class)->syncAgendaFromDemanda($demandaAcolhido);

        Notification::make()
            ->title('Demanda atualizada com sucesso')
            ->body('A demanda correspondente foi atualizada também na agenda.')
            ->sendToDatabase(User::all());
    }

    /**
     * Handle the DemandaAcolhido "deleted" event.
     */
    public function deleted(DemandaAcolhido $demandaAcolhido): void
    {
        app(AgendaService::class)->removeAgendaFromDemanda($demandaAcolhido);
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

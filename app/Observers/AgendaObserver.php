<?php

namespace App\Observers;

use App\Filament\Resources\Agendas\AgendaResource;
use App\Models\Agenda;

class AgendaObserver
{
    public function created(Agenda $agenda): void
    {
        AgendaResource::notifyUsers($agenda, 'created');
    }

    public function updated(Agenda $agenda): void
    {
        AgendaResource::notifyUsers($agenda, 'updated');
    }

    public function deleted(Agenda $agenda): void
    {
        AgendaResource::notifyUsers($agenda, 'deleted');
    }
}

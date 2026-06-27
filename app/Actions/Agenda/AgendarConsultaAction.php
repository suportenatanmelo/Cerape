<?php

namespace App\Actions\Agenda;

use App\Models\Agenda;
use App\Services\Agenda\AgendaService;
use Illuminate\Support\Facades\DB;

class AgendarConsultaAction
{
    public function __construct(
        private readonly AgendaService $agendaService,
    ) {
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Agenda
    {
        return DB::transaction(function () use ($data): Agenda {
            $agenda = Agenda::query()->create($data);

            $this->agendaService->getEventosDoDia($agenda->data);

            return $agenda;
        });
    }
}

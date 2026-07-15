<?php

namespace App\Filament\Resources\Agendas\Pages;

use App\Models\Agenda;
use App\Models\Acolhido;
use App\Models\User;
use App\Services\Agenda\AgendaService;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Carbon;

class CalendarAgenda extends Page
{
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Calendário';

    protected static ?string $title = 'Calendário de agendamentos';

    protected static ?string $slug = 'agendas/calendario';

    protected string $view = 'filament.resources.agendas.pages.calendar-agenda';

    public string $calendarView = 'month';

    public int $monthOffset = 0;

    public ?string $statusFilter = null;

    public ?string $funcionarioFilter = null;

    public ?int $acolhidoFilter = null;

    public static function canAccess(): bool
    {
        return true;
    }

    public function getTitle(): string | Htmlable
    {
        return 'Calendário assistencial';
    }

    public function mount(): void
    {
        $this->calendarView = 'month';
        $this->monthOffset = 0;
    }

    public function showMonthView(): void
    {
        $this->calendarView = 'month';
    }

    public function showWeekView(): void
    {
        $this->calendarView = 'week';
    }

    public function previousMonth(): void
    {
        $this->monthOffset--;
    }

    public function nextMonth(): void
    {
        $this->monthOffset++;
    }

    public function goToToday(): void
    {
        $this->monthOffset = 0;
    }

    public function clearFilters(): void
    {
        $this->statusFilter = null;
        $this->funcionarioFilter = null;
        $this->acolhidoFilter = null;
    }

    public function getMonthLabel(): string
    {
        return $this->getReferenceDate()->translatedFormat('F \\d\\e Y');
    }

    public function getLegend(): array
    {
        return [
            ['label' => 'Consulta', 'color' => '#3b82f6', 'hint' => 'Atendimentos clínicos e retornos.'],
            ['label' => 'Psicologia', 'color' => '#8b5cf6', 'hint' => 'Sessões e acompanhamentos psicológicos.'],
            ['label' => 'Assistência Social', 'color' => '#10b981', 'hint' => 'Ações de suporte social e familiar.'],
            ['label' => 'Visita', 'color' => '#f59e0b', 'hint' => 'Visitas, encontros e contatos presenciais.'],
        ];
    }

    /**
     * @return array<int, string>
     */
    public function getFuncionarioOptions(): array
    {
        return User::query()
            ->orderBy('name')
            ->pluck('name')
            ->all();
    }

    /**
     * @return array<int, string>
     */
    public function getAcolhidoOptions(): array
    {
        return Acolhido::query()
            ->orderBy('nome_completo_paciente')
            ->pluck('nome_completo_paciente', 'id')
            ->all();
    }

    public function getUpcomingEvents(int $limit = 6)
    {
        return $this->filteredAgendasQuery()
            ->whereDate('data', '>=', today())
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->limit($limit)
            ->get()
            ->all();
    }

    public function getSummary(): array
    {
        return app(AgendaService::class)->getResumo();
    }

    public function colorFor(Agenda $agenda): string
    {
        return app(AgendaService::class)->colorFor($agenda);
    }

    public function getReferenceDate(): Carbon
    {
        return now()->startOfMonth()->addMonthsNoOverflow($this->monthOffset);
    }

    /**
     * @return array<int, array{date: string, events: \Illuminate\Support\Collection<int, Agenda>}>
     */
    public function getCalendarWeeks()
    {
        $monthStart = $this->getReferenceDate()->copy()->startOfMonth();
        $monthEnd = $this->getReferenceDate()->copy()->endOfMonth();

        $events = $this->filteredAgendasQuery()
            ->whereBetween('data', [$monthStart->toDateString(), $monthEnd->toDateString()])
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy(fn (Agenda $agenda): string => $agenda->data->format('Y-m-d'));

        $cursor = $monthStart->copy()->startOfWeek(Carbon::MONDAY);
        $last = $monthEnd->copy()->endOfWeek(Carbon::SUNDAY);
        $weeks = [];

        while ($cursor->lte($last)) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $date = $cursor->copy();

                $week[] = [
                    'date' => $date,
                    'isCurrentMonth' => $date->month === $monthStart->month,
                    'events' => $events[$date->toDateString()] ?? collect(),
                ];

                $cursor->addDay();
            }

            $weeks[] = $week;
        }

        return $weeks;
    }

    /**
     * @return array<int, array{date: Carbon, isCurrentMonth: bool, events: \Illuminate\Support\Collection<int, Agenda>}>
     */
    public function getWeeklyDays(): array
    {
        $reference = $this->getReferenceDate()->copy()->startOfWeek(Carbon::MONDAY);
        $weekEnd = $reference->copy()->endOfWeek(Carbon::SUNDAY);

        $events = $this->filteredAgendasQuery()
            ->whereBetween('data', [$reference->toDateString(), $weekEnd->toDateString()])
            ->orderBy('data')
            ->orderBy('hora_inicio')
            ->get()
            ->groupBy(fn (Agenda $agenda): string => $agenda->data->format('Y-m-d'));

        $days = [];

        while ($reference->lte($weekEnd)) {
            $date = $reference->copy();

            $days[] = [
                'date' => $date,
                'isToday' => $date->isToday(),
                'events' => $events[$date->toDateString()] ?? collect(),
            ];

            $reference->addDay();
        }

        return $days;
    }

    /**
     * @return array<int, Agenda>
     */
    public function getTodayEvents()
    {
        return $this->filteredAgendasQuery()
            ->whereDate('data', today())
            ->orderBy('hora_inicio')
            ->get()
            ->all();
    }

    public function getWeekLabel(): string
    {
        $start = $this->getReferenceDate()->copy()->startOfWeek(Carbon::MONDAY);
        $end = $start->copy()->endOfWeek(Carbon::SUNDAY);

        return $start->translatedFormat('d \\d\\e F') . ' - ' . $end->translatedFormat('d \\d\\e F \\d\\e Y');
    }

    protected function filteredAgendasQuery()
    {
        $query = Agenda::query()->with(['acolhido', 'funcionario']);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if ($this->funcionarioFilter) {
            $query->whereHas('funcionario', function ($builder): void {
                $builder->where('name', $this->funcionarioFilter);
            });
        }

        if ($this->acolhidoFilter) {
            $query->where('acolhido_id', $this->acolhidoFilter);
        }

        return $query;
    }
}

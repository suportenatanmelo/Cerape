<x-filament-panels::page>
    <style>
        .agenda-calendar {
            display: grid;
            gap: 1.25rem;
        }

        .agenda-calendar__hero,
        .agenda-calendar__panel {
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.96));
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 1.5rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .agenda-calendar__hero {
            padding: 1.25rem 1.4rem;
        }

        .agenda-calendar__grid {
            display: grid;
            gap: 0.75rem;
            grid-template-columns: repeat(7, minmax(0, 1fr));
        }

        .agenda-calendar__weekday,
        .agenda-day,
        .agenda-weekday-card {
            border-radius: 1rem;
            border: 1px solid rgba(148, 163, 184, 0.18);
        }

        .agenda-calendar__weekday {
            background: rgba(15, 118, 110, 0.08);
            color: #0f172a;
            font-weight: 700;
            padding: 0.7rem 0.8rem;
            text-align: center;
        }

        .agenda-day,
        .agenda-weekday-card {
            background: #fff;
            min-height: 9rem;
            padding: 0.8rem;
        }

        .agenda-day--muted {
            background: rgba(248, 250, 252, 0.65);
            color: #94a3b8;
        }

        .agenda-day--today,
        .agenda-weekday-card--today {
            border-color: rgba(13, 148, 136, 0.45);
            box-shadow: inset 0 0 0 1px rgba(13, 148, 136, 0.12);
        }

        .agenda-day__number,
        .agenda-weekday-card__header {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.6rem;
        }

        .agenda-event {
            border-left: 4px solid var(--agenda-color, #3b82f6);
            border-radius: 0.8rem;
            background: rgba(248, 250, 252, 0.96);
            margin-bottom: 0.5rem;
            padding: 0.55rem 0.65rem;
        }

        .agenda-event__time {
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #475569;
        }

        .agenda-event__title {
            font-size: 0.92rem;
            font-weight: 700;
            color: #0f172a;
            margin-top: 0.15rem;
        }

        .agenda-event__meta {
            color: #64748b;
            font-size: 0.8rem;
            line-height: 1.4;
            margin-top: 0.15rem;
        }

        .agenda-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .agenda-legend__item {
            align-items: center;
            background: rgba(248, 250, 252, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.18);
            border-radius: 999px;
            display: inline-flex;
            gap: 0.45rem;
            padding: 0.45rem 0.7rem;
        }

        .agenda-legend__dot {
            border-radius: 999px;
            height: 0.7rem;
            width: 0.7rem;
        }

        @media (max-width: 1100px) {
            .agenda-calendar__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 720px) {
            .agenda-calendar__grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @php($weeks = $this->getCalendarWeeks())
    @php($weekDays = $this->getWeeklyDays())
    @php($todayEvents = $this->getTodayEvents())
    @php($legend = $this->getLegend())

    <div class="agenda-calendar">
        <div class="agenda-calendar__hero">
            <div class="text-sm font-semibold uppercase tracking-[0.24em] text-teal-700">Agenda</div>
            <h1 class="mt-2 text-2xl font-bold text-slate-950">{{ $this->getTitle() }}</h1>
            <p class="mt-2 text-slate-600">Use a navegação por mês ou a visão semanal para acompanhar os atendimentos com mais precisão.</p>

            <div class="mt-4 flex flex-wrap gap-2">
                <x-filament::button color="gray" icon="heroicon-o-chevron-left" wire:click="previousMonth">
                    Anterior
                </x-filament::button>
                <x-filament::button color="gray" wire:click="goToToday">
                    Hoje
                </x-filament::button>
                <x-filament::button color="gray" icon="heroicon-o-chevron-right" icon-position="after" wire:click="nextMonth">
                    Próximo
                </x-filament::button>
                <x-filament::button color="{{ $this->calendarView === 'month' ? 'primary' : 'gray' }}" wire:click="showMonthView">
                    Mês
                </x-filament::button>
                <x-filament::button color="{{ $this->calendarView === 'week' ? 'primary' : 'gray' }}" wire:click="showWeekView">
                    Semana
                </x-filament::button>
            </div>

            <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Acolhido</label>
                    <select wire:model.live="acolhidoFilter" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        <option value="">Todos</option>
                        @foreach ($this->getAcolhidoOptions() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-slate-700">Funcionário</label>
                    <select wire:model.live="funcionarioFilter" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500">
                        <option value="">Todos</option>
                        @foreach ($this->getFuncionarioOptions() as $name)
                            <option value="{{ $name }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                <x-filament::button color="gray" wire:click="clearFilters">
                    Limpar filtros
                </x-filament::button>
            </div>

            <div class="agenda-legend">
                @foreach ($legend as $item)
                    <span class="agenda-legend__item" title="{{ $item['hint'] }}">
                        <span class="agenda-legend__dot" style="background:{{ $item['color'] }}"></span>
                        {{ $item['label'] }}
                    </span>
                @endforeach
            </div>
        </div>

        <div class="agenda-calendar__panel p-4">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">
                        {{ $this->calendarView === 'week' ? 'Semana atual' : 'Mês atual' }}
                    </div>
                    <div class="text-xl font-bold text-slate-950">
                        {{ $this->calendarView === 'week' ? $this->getWeekLabel() : $this->getMonthLabel() }}
                    </div>
                </div>
                <div class="text-sm text-slate-500">Hoje: {{ now()->format('d/m/Y') }}</div>
            </div>

            @if ($this->calendarView === 'week')
                <div class="agenda-calendar__grid mb-3">
                    @foreach (['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'] as $weekday)
                        <div class="agenda-calendar__weekday">{{ $weekday }}</div>
                    @endforeach
                </div>

                <div class="agenda-calendar__grid">
                    @foreach ($weekDays as $day)
                        <div class="agenda-weekday-card {{ $day['isToday'] ? 'agenda-weekday-card--today' : '' }}">
                            <div class="agenda-weekday-card__header">
                                {{ $day['date']->translatedFormat('D, d/m') }}
                            </div>
                            @forelse ($day['events'] as $event)
                                <div class="agenda-event" style="--agenda-color: {{ $event->cor }};">
                                    <div class="agenda-event__time">
                                        {{ $event->dia_todo ? 'Dia todo' : $event->hora_inicio . ' - ' . $event->hora_fim }}
                                    </div>
                                    <div class="agenda-event__title">{{ $event->titulo }}</div>
                                    <div class="agenda-event__meta">
                                        {{ $event->acolhido?->nome_completo_paciente ?? 'Sem acolhido' }}
                                        @if ($event->funcionario?->name)
                                            <br>{{ $event->funcionario->name }}
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-xs text-slate-400">Sem eventos</div>
                            @endforelse
                        </div>
                    @endforeach
                </div>
            @else
                <div class="agenda-calendar__grid mb-3">
                    @foreach (['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'] as $weekday)
                        <div class="agenda-calendar__weekday">{{ $weekday }}</div>
                    @endforeach
                </div>

                <div class="space-y-3">
                    @foreach ($weeks as $week)
                        <div class="agenda-calendar__grid">
                            @foreach ($week as $day)
                                <div class="agenda-day {{ $day['isCurrentMonth'] ? '' : 'agenda-day--muted' }} {{ $day['date']->isToday() ? 'agenda-day--today' : '' }}">
                                    <div class="agenda-day__number">{{ $day['date']->format('d') }}</div>
                                    @forelse ($day['events'] as $event)
                                        <div class="agenda-event" style="--agenda-color: {{ $event->cor }};">
                                            <div class="agenda-event__time">
                                                {{ $event->dia_todo ? 'Dia todo' : $event->hora_inicio . ' - ' . $event->hora_fim }}
                                            </div>
                                            <div class="agenda-event__title">{{ $event->titulo }}</div>
                                            <div class="agenda-event__meta">
                                                {{ $event->acolhido?->nome_completo_paciente ?? 'Sem acolhido' }}
                                                @if ($event->funcionario?->name)
                                                    <br>{{ $event->funcionario->name }}
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-xs text-slate-400">Sem eventos</div>
                                    @endforelse
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="agenda-calendar__panel p-4">
            <div class="mb-3">
                <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Hoje</div>
                <div class="text-lg font-bold text-slate-950">{{ count($todayEvents) }} agendamento(s)</div>
            </div>
            <div class="space-y-3">
                @forelse ($todayEvents as $event)
                    <div class="agenda-event" style="--agenda-color: {{ $event->cor }};">
                        <div class="agenda-event__time">
                            {{ $event->dia_todo ? 'Dia todo' : $event->hora_inicio . ' - ' . $event->hora_fim }}
                        </div>
                        <div class="agenda-event__title">{{ $event->titulo }}</div>
                        <div class="agenda-event__meta">
                            {{ $event->tipo }} · {{ $event->status }}<br>
                            {{ $event->acolhido?->nome_completo_paciente ?? 'Sem acolhido' }}
                            @if ($event->funcionario?->name)
                                <br>{{ $event->funcionario->name }}
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-slate-500">Nenhum agendamento para hoje.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-filament-panels::page>

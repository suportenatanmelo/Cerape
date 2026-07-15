<x-filament-panels::page>
    <style>
        .agenda-calendar {
            --agenda-bg: rgb(255 255 255 / 0.98);
            --agenda-bg-soft: rgb(248 250 252 / 0.96);
            --agenda-surface: rgb(255 255 255 / 1);
            --agenda-border: rgb(148 163 184 / 0.18);
            --agenda-border-strong: rgb(148 163 184 / 0.24);
            --agenda-text: rgb(15 23 42 / 1);
            --agenda-text-soft: rgb(100 116 139 / 1);
            --agenda-text-muted: rgb(148 163 184 / 1);
            --agenda-brand: rgb(20 184 166 / 1);
            --agenda-brand-soft: rgb(20 184 166 / 0.12);
            --agenda-shadow: 0 18px 40px rgb(15 23 42 / 0.08);
            --agenda-card-shadow: 0 8px 20px rgb(15 23 42 / 0.08);
            --agenda-weekday-bg: rgb(15 118 110 / 0.08);
        }

        .dark .agenda-calendar {
            --agenda-bg: rgb(15 23 42 / 0.92);
            --agenda-bg-soft: rgb(15 23 42 / 0.78);
            --agenda-surface: rgb(30 41 59 / 0.96);
            --agenda-border: rgb(71 85 105 / 0.45);
            --agenda-border-strong: rgb(100 116 139 / 0.55);
            --agenda-text: rgb(226 232 240 / 1);
            --agenda-text-soft: rgb(203 213 225 / 0.9);
            --agenda-text-muted: rgb(148 163 184 / 0.9);
            --agenda-brand: rgb(45 212 191 / 1);
            --agenda-brand-soft: rgb(45 212 191 / 0.16);
            --agenda-shadow: 0 18px 40px rgb(2 6 23 / 0.45);
            --agenda-card-shadow: 0 8px 20px rgb(2 6 23 / 0.35);
            --agenda-weekday-bg: rgb(15 118 110 / 0.2);
        }

        .agenda-calendar {
            display: grid;
            gap: 1.25rem;
        }

        .agenda-calendar__hero,
        .agenda-calendar__panel,
        .agenda-calendar__sidebar {
            background: linear-gradient(180deg, var(--agenda-bg), var(--agenda-bg-soft));
            border: 1px solid var(--agenda-border);
            border-radius: 1.5rem;
            box-shadow: var(--agenda-shadow);
        }

        .agenda-calendar__hero {
            padding: 1.25rem 1.4rem;
        }

        .agenda-calendar__grid {
            display: grid;
            gap: 0.75rem;
            grid-template-columns: repeat(7, minmax(0, 1fr));
        }

        .agenda-calendar__content {
            display: grid;
            gap: 1.25rem;
            grid-template-columns: minmax(0, 1fr) 320px;
        }

        .agenda-calendar__weekday,
        .agenda-day,
        .agenda-weekday-card {
            border-radius: 1rem;
            border: 1px solid var(--agenda-border);
        }

        .agenda-calendar__weekday {
            background: var(--agenda-weekday-bg);
            color: var(--agenda-text);
            font-weight: 700;
            padding: 0.7rem 0.8rem;
            text-align: center;
        }

        .agenda-day,
        .agenda-weekday-card {
            background: var(--agenda-surface);
            min-height: 8.4rem;
            padding: 0.7rem;
        }

        .agenda-day--muted {
            background: rgb(148 163 184 / 0.08);
            color: var(--agenda-text-muted);
        }

        .agenda-day--today,
        .agenda-weekday-card--today {
            border-color: rgb(13 148 136 / 0.45);
            box-shadow: inset 0 0 0 1px rgb(13 148 136 / 0.14);
        }

        .agenda-day__number,
        .agenda-weekday-card__header {
            font-size: 0.95rem;
            font-weight: 700;
            margin-bottom: 0.6rem;
            color: var(--agenda-text);
        }

        .agenda-event {
            border-left: 4px solid var(--agenda-color, #3b82f6);
            border-radius: 0.8rem;
            background: var(--agenda-surface);
            margin-bottom: 0.45rem;
            padding: 0.5rem 0.6rem;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }

        .agenda-event a {
            display: block;
        }

        .agenda-event:hover {
            transform: translateY(-1px);
            box-shadow: var(--agenda-card-shadow);
        }

        .agenda-event__time {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--agenda-text-soft);
        }

        .agenda-event__title {
            font-size: 0.88rem;
            font-weight: 700;
            color: var(--agenda-text);
            margin-top: 0.15rem;
        }

        .agenda-event__meta {
            color: var(--agenda-text-soft);
            font-size: 0.76rem;
            line-height: 1.4;
            margin-top: 0.15rem;
        }

        .agenda-mini-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .agenda-sidebar-list {
            display: grid;
            gap: 0.7rem;
        }

        .agenda-sidebar-item {
            display: grid;
            gap: 0.25rem;
            grid-template-columns: 14px minmax(0, 1fr);
            padding: 0.75rem 0.8rem;
            border-radius: 1rem;
            border: 1px solid var(--agenda-border);
            background: var(--agenda-surface);
        }

        .agenda-sidebar-item__dot {
            width: 0.75rem;
            height: 0.75rem;
            border-radius: 999px;
            margin-top: 0.25rem;
            background: var(--agenda-color, #3b82f6);
        }

        .agenda-sidebar-item__title {
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--agenda-text);
        }

        .agenda-sidebar-item__meta {
            font-size: 0.78rem;
            color: var(--agenda-text-soft);
            line-height: 1.4;
        }

        .agenda-calendar__quick {
            display: grid;
            gap: 0.6rem;
        }

        .agenda-calendar__quick a,
        .agenda-calendar__quick button {
            width: 100%;
        }

        .agenda-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .agenda-stats {
            display: grid;
            gap: 0.75rem;
            grid-template-columns: repeat(6, minmax(0, 1fr));
        }

        .agenda-stat {
            border-radius: 1rem;
            border: 1px solid var(--agenda-border);
            background: var(--agenda-surface);
            padding: 0.85rem 0.95rem;
        }

        .agenda-stat__label {
            color: var(--agenda-text-soft);
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .agenda-stat__value {
            color: var(--agenda-text);
            font-size: 1.5rem;
            font-weight: 800;
            margin-top: 0.2rem;
        }

        .agenda-stat__meta {
            color: var(--agenda-text-soft);
            font-size: 0.8rem;
            margin-top: 0.2rem;
        }

        .agenda-calendar__badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            border-radius: 999px;
            padding: 0.3rem 0.65rem;
            background: var(--agenda-brand-soft);
            color: var(--agenda-brand);
            font-weight: 700;
            font-size: 0.78rem;
        }

        .agenda-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .agenda-legend__item {
            align-items: center;
            background: var(--agenda-surface);
            border: 1px solid var(--agenda-border);
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
            .agenda-calendar__content {
                grid-template-columns: 1fr;
            }

            .agenda-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .agenda-calendar__grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 720px) {
            .agenda-stats {
                grid-template-columns: 1fr;
            }

            .agenda-calendar__grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    @php($weeks = $this->getCalendarWeeks())
    @php($weekDays = $this->getWeeklyDays())
    @php($todayEvents = $this->getTodayEvents())
    @php($upcomingEvents = $this->getUpcomingEvents())
    @php($legend = $this->getLegend())
    @php($createUrl = \App\Filament\Resources\Agendas\AgendaResource::getUrl('create'))
    @php($summary = $this->getSummary())

    <div class="agenda-calendar">
        <div class="agenda-calendar__hero">
            <div class="agenda-toolbar">
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.24em] text-[color:var(--agenda-text-soft)]">Agenda</div>
                    <h1 class="mt-2 text-2xl font-bold text-[color:var(--agenda-text)]">Gerencie compromissos, consultas e atendimentos.</h1>
                    <p class="mt-2 max-w-3xl text-[color:var(--agenda-text-soft)]">
                        Interface responsiva no padrão Filament, com navegação por período, filtros em tempo real e agenda lateral.
                    </p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        <span class="agenda-calendar__badge">{{ count($todayEvents) }} eventos hoje</span>
                        <span class="agenda-calendar__badge">{{ count($upcomingEvents) }} próximos eventos</span>
                    </div>
                </div>
                <div class="agenda-calendar__quick lg:w-[260px]">
                    <x-filament::button tag="a" :href="$createUrl" color="primary" icon="heroicon-o-plus-circle" icon-position="before" class="h-12">
                        Novo evento
                    </x-filament::button>
                    <x-filament::button color="gray" icon="heroicon-o-arrow-path" wire:click="$refresh" class="h-12">
                        Atualizar agenda
                    </x-filament::button>
                </div>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
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

            <div class="mt-5 agenda-stats">
                <div class="agenda-stat">
                    <div class="agenda-stat__label">Agendamentos hoje</div>
                    <div class="agenda-stat__value">{{ $summary['todayEvents'] }}</div>
                    <div class="agenda-stat__meta">Total do dia atual</div>
                </div>
                <div class="agenda-stat">
                    <div class="agenda-stat__label">Consultas</div>
                    <div class="agenda-stat__value">{{ $summary['consultas'] }}</div>
                    <div class="agenda-stat__meta">Atendimentos clínicos</div>
                </div>
                <div class="agenda-stat">
                    <div class="agenda-stat__label">Reuniões</div>
                    <div class="agenda-stat__value">{{ $summary['reunioes'] }}</div>
                    <div class="agenda-stat__meta">Equipe e gestão</div>
                </div>
                <div class="agenda-stat">
                    <div class="agenda-stat__label">Visitas</div>
                    <div class="agenda-stat__value">{{ $summary['visitas'] }}</div>
                    <div class="agenda-stat__meta">Visitas agendadas</div>
                </div>
                <div class="agenda-stat">
                    <div class="agenda-stat__label">Plantões</div>
                    <div class="agenda-stat__value">{{ $summary['plantoes'] }}</div>
                    <div class="agenda-stat__meta">Cobertura assistencial</div>
                </div>
                <div class="agenda-stat">
                    <div class="agenda-stat__label">Eventos</div>
                    <div class="agenda-stat__value">{{ $summary['eventos'] }}</div>
                    <div class="agenda-stat__meta">Compromissos totais</div>
                </div>
            </div>
        </div>

        <div class="agenda-calendar__content">
            <div class="agenda-calendar__panel p-4">
                <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--agenda-text-soft)]">
                            {{ $this->calendarView === 'week' ? 'Semana atual' : 'Mês atual' }}
                        </div>
                        <div class="text-xl font-bold text-[color:var(--agenda-text)]">
                            {{ $this->calendarView === 'week' ? $this->getWeekLabel() : $this->getMonthLabel() }}
                        </div>
                    </div>
                    <div class="text-sm text-[color:var(--agenda-text-soft)]">Hoje: {{ now()->format('d/m/Y') }}</div>
                </div>

                <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    <div>
                        <label class="mb-1 block text-sm font-medium text-[color:var(--agenda-text-soft)]">Acolhido</label>
                        <select wire:model.live="acolhidoFilter" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                            <option value="">Todos</option>
                            @foreach ($this->getAcolhidoOptions() as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-[color:var(--agenda-text-soft)]">Funcionário</label>
                        <select wire:model.live="funcionarioFilter" class="w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-teal-500 focus:ring-teal-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                            <option value="">Todos</option>
                            @foreach ($this->getFuncionarioOptions() as $name)
                                <option value="{{ $name }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <x-filament::button color="gray" wire:click="clearFilters" class="w-full">
                            Limpar filtros
                        </x-filament::button>
                    </div>
                </div>

                <div class="agenda-legend">
                    @foreach ($legend as $item)
                        <span class="agenda-legend__item" title="{{ $item['hint'] }}">
                            <span class="agenda-legend__dot" style="background:{{ $item['color'] }}"></span>
                            {{ $item['label'] }}
                        </span>
                    @endforeach
                </div>

                <div class="mt-5">
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
                                        <a class="agenda-event" href="{{ \App\Filament\Resources\Agendas\AgendaResource::getUrl('view', ['record' => $event]) }}" style="--agenda-color: {{ $this->colorFor($event) }};">
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
                                        </a>
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
                                                <a class="agenda-event" href="{{ \App\Filament\Resources\Agendas\AgendaResource::getUrl('view', ['record' => $event]) }}" style="--agenda-color: {{ $this->colorFor($event) }};">
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
                                                </a>
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
            </div>

            <aside class="agenda-calendar__sidebar p-4">
                <div class="agenda-mini-header">
                    <div>
                        <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--agenda-text-soft)]">Mini agenda</div>
                        <div class="text-lg font-bold text-[color:var(--agenda-text)]">Hoje</div>
                    </div>
                    <span class="agenda-calendar__badge">{{ count($todayEvents) }} itens</span>
                </div>

                <div class="mt-4 agenda-sidebar-list">
                    @forelse ($todayEvents as $event)
                        <a class="agenda-sidebar-item" href="{{ \App\Filament\Resources\Agendas\AgendaResource::getUrl('view', ['record' => $event]) }}" style="--agenda-color: {{ $this->colorFor($event) }};">
                            <span class="agenda-sidebar-item__dot"></span>
                            <div>
                                <div class="agenda-sidebar-item__title">
                                    {{ $event->dia_todo ? 'Dia todo' : $event->hora_inicio . ' - ' . $event->hora_fim }}
                                </div>
                                <div class="agenda-sidebar-item__meta">
                                    {{ $event->titulo }}<br>
                                    {{ $event->acolhido?->nome_completo_paciente ?? 'Sem acolhido' }}
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="text-sm text-slate-500">Nenhum agendamento para hoje.</div>
                    @endforelse
                </div>

                <div class="mt-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Próximos agendamentos</div>
                    <div class="mt-3 space-y-2">
                        @forelse ($upcomingEvents as $event)
                            <a class="agenda-sidebar-item" href="{{ \App\Filament\Resources\Agendas\AgendaResource::getUrl('view', ['record' => $event]) }}" style="--agenda-color: {{ $this->colorFor($event) }};">
                                <span class="agenda-sidebar-item__dot"></span>
                                <div>
                                    <div class="agenda-sidebar-item__title">{{ $event->titulo }}</div>
                                    <div class="agenda-sidebar-item__meta">
                                        {{ $event->data?->format('d/m') }} {{ $event->hora_inicio }}<br>
                                        {{ $event->funcionario?->name ?? 'Sem responsável' }}
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-sm text-slate-500">Sem próximos agendamentos.</div>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.2em] text-[color:var(--agenda-text-soft)]">Legenda</div>
                    <div class="mt-3 space-y-2">
                        @foreach ($legend as $item)
                            <div class="agenda-sidebar-item">
                                <span class="agenda-sidebar-item__dot" style="--agenda-color: {{ $item['color'] }}; background: {{ $item['color'] }};"></span>
                                <div>
                                    <div class="agenda-sidebar-item__title">{{ $item['label'] }}</div>
                                    <div class="agenda-sidebar-item__meta">{{ $item['hint'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</x-filament-panels::page>

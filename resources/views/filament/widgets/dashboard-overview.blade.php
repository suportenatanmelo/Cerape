<x-filament-widgets::widget>
    <div class="dashboard-overview-shell p-6 sm:p-8">
        <div class="dashboard-overview-header">
            <div>
                <div class="dashboard-overview-kicker">Painel CERAPE</div>
                <h1 class="dashboard-overview-title">Olá, {{ $greetingName }}.</h1>
                <p class="dashboard-overview-subtitle">Resumo executivo com os principais indicadores da operação e acesso rápido aos itens mais importantes do painel.</p>
            </div>

            <div class="dashboard-overview-summary-panel">
                <div class="dashboard-overview-summary-label">Foco do dia</div>
                <div class="dashboard-overview-summary-value">Acompanhar entradas e compromissos</div>
                <p class="dashboard-overview-summary-note">Os dados a seguir foram selecionados para oferecer clareza imediata sobre a operação atual.</p>
            </div>
        </div>

        <div class="mt-10 dashboard-overview-cards">
            <div class="dashboard-overview-card">
                <div class="dashboard-overview-card-label">Acolhidos hoje</div>
                <div class="dashboard-overview-card-value">{{ $cards['total_acolhidos'] }}</div>
                <div class="dashboard-overview-card-note">Total de cadastros ativos no sistema.</div>
            </div>

            <div class="dashboard-overview-card">
                <div class="dashboard-overview-card-label">Em acompanhamento</div>
                <div class="dashboard-overview-card-value">{{ $cards['acolhidos_ativos'] }}</div>
                <div class="dashboard-overview-card-note">Pessoas com status ativo sendo acompanhadas.</div>
            </div>

            <div class="dashboard-overview-card">
                <div class="dashboard-overview-card-label">Novos no mês</div>
                <div class="dashboard-overview-card-value">{{ $cards['novos_acolhimentos_mes'] }}</div>
                <div class="dashboard-overview-card-note">Entradas de acolhidos registradas no mês atual.</div>
            </div>

            <div class="dashboard-overview-card">
                <div class="dashboard-overview-card-label">Consultas hoje</div>
                <div class="dashboard-overview-card-value">{{ $cards['consultas_hoje'] }}</div>
                <div class="dashboard-overview-card-note">Consultas agendadas para hoje.</div>
            </div>
        </div>

        <div class="mt-10 dashboard-overview-panel">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3>Aniversariantes do mês</h3>
                    <p class="dashboard-overview-subtitle">Fotos e nomes dos próximos aniversariantes, com foco em identificação rápida e elegante.</p>
                </div>
                <span class="dashboard-overview-summary-label">{{ count($birthdays) }} no mês</span>
            </div>

            <div class="dashboard-overview-birthday-list mt-6">
                @forelse ($birthdays as $birthday)
                    @php
                        $avatar = $birthday['avatar'] ? route('media.serve', ['path' => ltrim($birthday['avatar'], '/')]) : 'https://ui-avatars.com/api/?name=' . urlencode($birthday['name']);
                    @endphp

                    <div class="dashboard-overview-birthday-item">
                        <img src="{{ $avatar }}" alt="{{ $birthday['name'] }}" class="dashboard-overview-birthday-avatar" loading="lazy">

                        <div class="min-w-0 flex-1">
                            <div class="dashboard-overview-birthday-name">{{ $birthday['name'] }}</div>
                            <div class="dashboard-overview-birthday-meta">{{ $birthday['role'] }} · {{ $birthday['birthday'] }} · {{ $birthday['age'] }} anos</div>
                        </div>

                        <span class="dashboard-overview-birthday-tag">{{ $birthday['label'] }}</span>
                    </div>
                @empty
                    <div class="dashboard-overview-list-item">
                        <div class="dashboard-overview-list-item-title">Nenhum aniversariante neste mês</div>
                        <div class="dashboard-overview-list-item-meta">O dashboard exibirá fotos assim que surgirem novos aniversariantes.</div>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="mt-10 dashboard-overview-secondary">
            <section class="dashboard-overview-panel">
                <h3>Agenda de hoje</h3>
                <div class="dashboard-overview-list">
                    @forelse ($upcomingAppointments as $appointment)
                        <div class="dashboard-overview-list-item">
                            <div class="dashboard-overview-list-item-title">{{ $appointment->acolhido?->nome_completo_paciente ?? 'Sem nome' }}</div>
                            <div class="dashboard-overview-list-item-meta">{{ $appointment->hora_inicio ? \Illuminate\Support\Carbon::parse($appointment->hora_inicio)->format('H:i') : '--:--' }} · {{ $appointment->tipo ?? 'Atendimento' }}</div>
                        </div>
                    @empty
                        <div class="dashboard-overview-list-item">
                            <div class="dashboard-overview-list-item-title">Nenhum compromisso encontrado</div>
                            <div class="dashboard-overview-list-item-meta">Revise a agenda para o dia ou cadastre um novo agendamento.</div>
                        </div>
                    @endforelse
                </div>
            </section>

            <aside class="dashboard-overview-panel">
                <h3>Ações rápidas</h3>
                <div class="dashboard-overview-action-list">
                    @foreach ($actions as $action)
                        <a href="{{ $action['url'] }}" class="dashboard-overview-action-link">{{ $action['label'] }}</a>
                    @endforeach
                </div>
            </aside>
        </div>
    </div>
</x-filament-widgets::widget>

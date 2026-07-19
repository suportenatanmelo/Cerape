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
                <div class="dashboard-overview-card-label">Novos no período</div>
                <div class="dashboard-overview-card-value">{{ $cards['novos_periodo'] }}</div>
                <div class="dashboard-overview-card-note">Entradas de acolhidos no intervalo selecionado.</div>
            </div>

            <div class="dashboard-overview-card">
                <div class="dashboard-overview-card-label">Consultas hoje</div>
                <div class="dashboard-overview-card-value">{{ $cards['consultas_hoje'] }}</div>
                <div class="dashboard-overview-card-note">Consultas agendadas para hoje.</div>
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
                <h3>Leitura estratégica</h3>
                <p class="dashboard-overview-subtitle">Os principais números da operação com foco em atenção rápida e decisão imediata.</p>
            </aside>
        </div>
    </div>
</x-filament-widgets::widget>

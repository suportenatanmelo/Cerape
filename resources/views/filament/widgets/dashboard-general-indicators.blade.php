<x-filament-widgets::widget>
    <div class="dashboard-widget-shell p-5 sm:p-6">
        <div class="dashboard-widget-header">
            <div>
                <div class="dashboard-widget-kicker">Visão complementar</div>
                <h3 class="dashboard-widget-title">Indicadores gerais</h3>
                <p class="dashboard-widget-subtitle">Métricas de apoio para acompanhar a operação sem poluir a leitura principal.</p>
            </div>
        </div>

        <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-6">
            <div class="dashboard-summary-card xl:col-span-2">
                <div class="dashboard-summary-label">Taxa de ocupação</div>
                <div class="dashboard-summary-value">{{ $taxaOcupacao }}%</div>
                <div class="dashboard-summary-note">capacidade operacional do período</div>
            </div>

            <div class="dashboard-summary-card xl:col-span-2">
                <div class="dashboard-summary-label">Média de permanência</div>
                <div class="dashboard-summary-value">{{ $mediaPermanencia }} dias</div>
                <div class="dashboard-summary-note">tempo médio de acompanhamento</div>
            </div>

            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Altas no mês</div>
                <div class="dashboard-summary-value">{{ $altasMes }}</div>
                <div class="dashboard-summary-note">saídas registradas</div>
            </div>

            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Desistências</div>
                <div class="dashboard-summary-value">{{ $desistencias }}</div>
                <div class="dashboard-summary-note">casos acompanhados</div>
            </div>

            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Consultas realizadas</div>
                <div class="dashboard-summary-value">{{ $consultasRealizadas }}</div>
                <div class="dashboard-summary-note">volume acumulado</div>
            </div>

            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Usuários ativos</div>
                <div class="dashboard-summary-value">{{ $usuariosAtivos }}</div>
                <div class="dashboard-summary-note">acessos liberados</div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>

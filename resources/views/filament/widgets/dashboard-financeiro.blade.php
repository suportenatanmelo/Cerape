<x-filament-widgets::widget>
    <div class="dashboard-widget-shell p-5 sm:p-6">
        <div class="dashboard-widget-header">
            <div>
                <div class="dashboard-widget-kicker">Financeiro</div>
                <h3 class="dashboard-widget-title">Indicadores gerais</h3>
                <p class="dashboard-widget-subtitle">Resumo executivo para leitura rápida do caixa e das obrigações.</p>
            </div>
        </div>

        <div class="mt-5 rounded-[1.35rem] border border-gray-200/80 bg-gray-950 px-5 py-5 text-white shadow-sm dark:border-gray-800">
            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-white/70">Saldo atual</div>
            <div class="mt-3 text-3xl font-semibold tracking-tight">{{ $saldo }}</div>
            <div class="mt-2 text-sm text-white/70">Visão consolidada do saldo informado no painel.</div>
        </div>

        <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Receitas</div>
                <div class="dashboard-summary-value">{{ $receitas }}</div>
                <div class="dashboard-summary-note">entradas registradas</div>
            </div>

            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Despesas</div>
                <div class="dashboard-summary-value">{{ $despesas }}</div>
                <div class="dashboard-summary-note">saídas registradas</div>
            </div>

            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Contas a pagar</div>
                <div class="dashboard-summary-value text-[1.5rem]">{{ $contasPagar }}</div>
                <div class="dashboard-summary-note">obrigações em aberto</div>
            </div>

            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Contas a receber</div>
                <div class="dashboard-summary-value text-[1.5rem]">{{ $contasReceber }}</div>
                <div class="dashboard-summary-note">recebíveis pendentes</div>
            </div>
        </div>
    </div>
</x-filament-widgets::widget>

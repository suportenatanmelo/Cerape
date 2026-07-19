<x-filament-widgets::widget>
    @php
        $alertClasses = [
            'danger' => 'dashboard-badge--danger',
            'warning' => 'dashboard-badge--warning',
            'primary' => 'dashboard-badge--neutral',
            'gray' => 'dashboard-badge--neutral',
            'success' => 'dashboard-badge--success',
        ];
        $alertLabels = [
            'danger' => 'Crítico',
            'warning' => 'Atenção',
            'primary' => 'Informativo',
            'gray' => 'Neutro',
            'success' => 'Saudável',
        ];
    @endphp

    <div class="dashboard-widget-shell p-5 sm:p-6">
        <div class="dashboard-widget-header">
            <div>
                <div class="dashboard-widget-kicker">Sinais</div>
                <h3 class="dashboard-widget-title">Alertas</h3>
                <p class="dashboard-widget-subtitle">Resumo dos pontos de atenção que precisam de leitura rápida.</p>
            </div>
        </div>

        <div class="mt-5 grid gap-3 sm:grid-cols-2">
            @foreach ($items as $item)
                <div class="dashboard-summary-card flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <div class="dashboard-summary-label">{{ $item['label'] }}</div>
                        <div class="dashboard-summary-value mt-2 text-[1.55rem]">{{ $item['value'] }}</div>
                    </div>
                    <span class="dashboard-badge {{ $alertClasses[$item['color']] ?? 'dashboard-badge--neutral' }} shrink-0">
                        {{ $alertLabels[$item['color']] ?? 'Info' }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>

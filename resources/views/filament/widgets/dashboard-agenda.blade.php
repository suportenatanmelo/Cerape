<x-filament-widgets::widget>
    @php
        $statusStyles = [
            'Confirmado' => 'dashboard-badge--success',
            'Agendado' => 'dashboard-badge--warning',
            'Em andamento' => 'dashboard-badge--neutral',
            'Concluído' => 'dashboard-badge--success',
            'Cancelado' => 'dashboard-badge--danger',
            'Faltou' => 'dashboard-badge--danger',
        ];
    @endphp

    <div class="dashboard-widget-shell p-5 sm:p-6">
        <div class="dashboard-widget-header">
            <div>
                <div class="dashboard-widget-kicker">Agenda</div>
                <h3 class="dashboard-widget-title">Agenda de hoje</h3>
                <p class="dashboard-widget-subtitle">Visão rápida dos compromissos do dia, com acesso direto ao atendimento.</p>
            </div>

            <x-filament::button tag="a" :href="\App\Filament\Resources\Agendas\AgendaResource::getUrl('index')" color="gray" size="sm">
                Ver agenda completa
            </x-filament::button>
        </div>

        <div class="mt-5 grid gap-3 sm:grid-cols-3">
            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Total</div>
                <div class="dashboard-summary-value">{{ $summary['total'] }}</div>
                <div class="dashboard-summary-note">compromissos hoje</div>
            </div>
            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Confirmados</div>
                <div class="dashboard-summary-value">{{ $summary['confirmados'] }}</div>
                <div class="dashboard-summary-note">itens validados</div>
            </div>
            <div class="dashboard-summary-card">
                <div class="dashboard-summary-label">Agendados</div>
                <div class="dashboard-summary-value">{{ $summary['agendados'] }}</div>
                <div class="dashboard-summary-note">ainda na fila</div>
            </div>
        </div>

        <div class="dashboard-table-shell mt-5">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                <thead class="text-left text-xs uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Horário</th>
                        <th class="px-4 py-3">Acolhido</th>
                        <th class="px-4 py-3">Profissional</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white/80 dark:divide-gray-800 dark:bg-gray-950/25">
                    @forelse ($items as $item)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $item->hora_inicio ? \Illuminate\Support\Carbon::parse($item->hora_inicio)->format('H:i') : '--:--' }}
                            </td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">
                                {{ $item->acolhido?->nome_completo_paciente ?? 'Sem nome' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                {{ $item->funcionario?->name ?? 'Sistema' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                                {{ $item->tipo ?? 'Atendimento' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="dashboard-badge {{ $statusStyles[$item->status ?? 'Confirmado'] ?? 'dashboard-badge--neutral' }}">
                                    {{ $item->status ?? 'Confirmado' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <x-filament::button tag="a" :href="\App\Filament\Resources\Agendas\AgendaResource::getUrl('view', ['record' => $item])" size="sm" color="gray">
                                    Abrir
                                </x-filament::button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-center text-gray-500 dark:text-gray-400" colspan="6">
                                Nenhum compromisso para hoje.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-filament-widgets::widget>

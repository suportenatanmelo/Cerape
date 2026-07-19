<x-filament-widgets::widget>
    <div class="dashboard-widget-shell p-5 sm:p-6">
        <div class="dashboard-widget-header">
            <div>
                <div class="dashboard-widget-kicker">Fluxo</div>
                <h3 class="dashboard-widget-title">Últimas atividades</h3>
                <p class="dashboard-widget-subtitle">Linha do tempo recente para acompanhar cadastros, consultas e prontuários.</p>
            </div>

            <x-filament::button tag="a" :href="\App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource::getUrl('index')" color="gray" size="sm">
                Ver todas
            </x-filament::button>
        </div>

        <div class="dashboard-table-shell mt-5">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                <thead class="text-left text-xs uppercase tracking-[0.18em] text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Usuário</th>
                        <th class="px-4 py-3">Ação</th>
                        <th class="px-4 py-3">Módulo</th>
                        <th class="px-4 py-3">Data</th>
                        <th class="px-4 py-3">Hora</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white/80 dark:divide-gray-800 dark:bg-gray-950/25">
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ $item['usuario'] }}</td>
                            <td class="px-4 py-3 text-gray-700 dark:text-gray-200">{{ $item['acao'] }}</td>
                            <td class="px-4 py-3">
                                <span class="dashboard-badge dashboard-badge--neutral">{{ $item['modulo'] }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $item['data'] }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $item['hora'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament-widgets::widget>

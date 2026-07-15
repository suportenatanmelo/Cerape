<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Últimas Atividades</x-slot>
        <x-slot name="headerEnd">
            <x-filament::button tag="a" :href="\App\Filament\Resources\ProntuariosEvolucao\ProntuarioEvolucaoResource::getUrl('index')" color="warning" size="sm">
                Ver todas
            </x-filament::button>
        </x-slot>

        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 text-left text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Usuário</th>
                        <th class="px-4 py-3">Ação</th>
                        <th class="px-4 py-3">Módulo</th>
                        <th class="px-4 py-3">Data</th>
                        <th class="px-4 py-3">Hora</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-900">
                    @foreach ($items as $item)
                        <tr>
                            <td class="px-4 py-3">{{ $item['usuario'] }}</td>
                            <td class="px-4 py-3">{{ $item['acao'] }}</td>
                            <td class="px-4 py-3">{{ $item['modulo'] }}</td>
                            <td class="px-4 py-3">{{ $item['data'] }}</td>
                            <td class="px-4 py-3">{{ $item['hora'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

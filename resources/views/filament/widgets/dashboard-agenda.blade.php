<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Agenda de Hoje</x-slot>
        <x-filament::button tag="a" :href="\App\Filament\Resources\Agendas\AgendaResource::getUrl('index')" color="warning" size="sm">
            Ver agenda completa
        </x-filament::button>

        <div class="mt-4 overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-700">
                <thead class="bg-gray-50 text-left text-gray-500 dark:bg-gray-800 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Horário</th>
                        <th class="px-4 py-3">Acolhido</th>
                        <th class="px-4 py-3">Profissional</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white dark:divide-gray-800 dark:bg-gray-900">
                    @forelse ($items as $item)
                        <tr>
                            <td class="px-4 py-3">{{ $item->hora_inicio ? \Illuminate\Support\Carbon::parse($item->hora_inicio)->format('H:i') : '--:--' }}</td>
                            <td class="px-4 py-3">{{ $item->acolhido?->nome_completo_paciente ?? 'Sem nome' }}</td>
                            <td class="px-4 py-3">{{ $item->funcionario?->name ?? 'Sistema' }}</td>
                            <td class="px-4 py-3">{{ $item->tipo ?? 'Atendimento' }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">
                                    {{ $item->status ?? 'Confirmado' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <x-filament::button tag="a" :href="\App\Filament\Resources\Agendas\AgendaResource::getUrl('view', ['record' => $item])" size="sm">
                                    Abrir Atendimento
                                </x-filament::button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-4 py-6 text-gray-500" colspan="6">Nenhum compromisso para hoje.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

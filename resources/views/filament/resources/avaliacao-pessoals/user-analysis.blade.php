<div class="space-y-4">
    <div class="grid gap-3 md:grid-cols-3">
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-sm text-gray-500 dark:text-gray-400">Media de todos</div>
            <div class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ $formatScore((float) $mediaDeTodos) }}
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-sm text-gray-500 dark:text-gray-400">Usuarios avaliadores</div>
            <div class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ $totalAvaliadores }}
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-sm text-gray-500 dark:text-gray-400">Avaliacoes registradas</div>
            <div class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ $avaliacoes->count() }}
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-950 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Usuario</th>
                        <th class="px-4 py-3">Qtd.</th>
                        <th class="px-4 py-3">Media</th>
                        <th class="px-4 py-3">Controle</th>
                        <th class="px-4 py-3">Autonomia</th>
                        <th class="px-4 py-3">Transparencia</th>
                        <th class="px-4 py-3">Superacao</th>
                        <th class="px-4 py-3">Autocuidado</th>
                        <th class="px-4 py-3">Ultima avaliacao</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($usuarios as $item)
                        <tr class="text-gray-700 dark:text-gray-200">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($item['foto'])
                                        <img src="{{ $item['foto'] }}" alt="" class="h-10 w-10 rounded-full object-cover">
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                            {{ str($item['user']?->name ?? '?')->substr(0, 1)->upper() }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-medium text-gray-950 dark:text-white">{{ $item['user']?->name ?? 'Usuario nao informado' }}</div>
                                        <div class="text-xs text-gray-500">{{ $item['user']?->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">{{ $item['quantidade'] }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $formatScore((float) $item['media']) }}</td>
                            @foreach ($item['criterios'] as $media)
                                <td class="px-4 py-3">{{ $formatScore((float) $media) }}</td>
                            @endforeach
                            <td class="px-4 py-3">{{ $item['ultima_avaliacao']?->created_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-gray-500">
                                Nenhuma avaliacao registrada para este acolhido.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

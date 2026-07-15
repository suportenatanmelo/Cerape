<div class="space-y-5">
    <div class="grid gap-3 md:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-sm text-gray-500 dark:text-gray-400">Media geral consolidada</div>
            <div class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ $formatScore((float) $mediaDeTodos) }}
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-sm text-gray-500 dark:text-gray-400">Soma das medias individuais limitada a 3</div>
            <div class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ number_format((float) $somaMediasIndividuais, 2, ',', '.') }}
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-sm text-gray-500 dark:text-gray-400">Profissionais avaliadores</div>
            <div class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ $totalAvaliadores }}
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <div class="text-sm text-gray-500 dark:text-gray-400">Avaliacoes registradas</div>
            <div class="mt-1 text-2xl font-semibold text-gray-950 dark:text-white">
                {{ $avaliacoes->count() }}
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse ($usuarios as $item)
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-4 border-b border-gray-200 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-950/60 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-center gap-3">
                        @if ($item['foto'])
                            <img src="{{ $item['foto'] }}" alt="" class="h-12 w-12 rounded-full object-cover">
                        @else
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                {{ str($item['user']?->name ?? '?')->substr(0, 1)->upper() }}
                            </div>
                        @endif

                        <div>
                            <div class="font-semibold text-gray-950 dark:text-white">
                                {{ $item['user']?->name ?? 'Profissional nao informado' }}
                            </div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $item['user']?->email ?? 'Sem e-mail cadastrado' }}
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3">
                        <div class="rounded-xl bg-white px-4 py-3 text-center shadow-sm dark:bg-gray-900">
                            <div class="text-xs uppercase tracking-wide text-gray-500">Qtd. de votos</div>
                            <div class="mt-1 text-lg font-semibold text-gray-950 dark:text-white">{{ $item['quantidade'] }}</div>
                        </div>

                        <div class="rounded-xl bg-white px-4 py-3 text-center shadow-sm dark:bg-gray-900">
                            <div class="text-xs uppercase tracking-wide text-gray-500">Media individual</div>
                            <div class="mt-1 text-lg font-semibold text-gray-950 dark:text-white">{{ $formatScore((float) $item['media']) }}</div>
                        </div>

                        <div class="rounded-xl bg-white px-4 py-3 text-center shadow-sm dark:bg-gray-900">
                            <div class="text-xs uppercase tracking-wide text-gray-500">Ultima participacao</div>
                            <div class="mt-1 text-sm font-semibold text-gray-950 dark:text-white">
                                {{ $item['ultima_avaliacao']?->created_at?->format('d/m/Y H:i') ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-4 p-5 lg:grid-cols-5">
                    @foreach ($item['criterios'] as $label => $media)
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950">
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                {{ $label }}
                            </div>
                            <div class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">
                                {{ $formatScore((float) $media) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="rounded-xl border border-dashed border-gray-300 bg-white p-8 text-center text-sm text-gray-500 shadow-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400">
                Nenhum profissional registrou avaliacao para este acolhido ate o momento.
            </div>
        @endforelse
    </div>
</div>

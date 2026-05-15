<x-filament-panels::page>
    <div class="grid gap-6 xl:grid-cols-[360px_minmax(0,1fr)]">
        <div class="space-y-6">
            <x-filament::section>
                <div class="space-y-5">
                    <div class="flex flex-col items-center rounded-2xl border border-gray-200 bg-gradient-to-b from-amber-50 to-white p-6 text-center shadow-sm dark:border-gray-800 dark:from-gray-900 dark:to-gray-950">
                        @if ($fotoAcolhido)
                            <img src="{{ $fotoAcolhido }}" alt="" class="h-44 w-32 rounded-2xl border-4 border-white object-cover shadow-md">
                        @else
                            <div class="flex h-44 w-32 items-center justify-center rounded-2xl border-4 border-white bg-gray-100 text-3xl font-semibold text-gray-500 shadow-md dark:bg-gray-800 dark:text-gray-300">
                                {{ str($acolhido?->nome_completo_paciente ?? '?')->substr(0, 1)->upper() }}
                            </div>
                        @endif

                        <div class="mt-4 text-lg font-semibold text-gray-950 dark:text-white">
                            {{ $acolhido?->nome_completo_paciente ?? 'Acolhido nao informado' }}
                        </div>

                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $record->dias_na_casa ?? '-' }}
                        </div>

                        <div class="mt-4 inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">
                            Relatório profissional de evolução pessoal
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-1">
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-sm text-gray-500 dark:text-gray-400">MÃ©dia geral consolidada</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white">{{ $formatScore((float) $médiaDeTodos) }}</div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Soma das médias individuais limitada a 3</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white">{{ number_format((float) $somaMédiasIndividuais, 2, ',', '.') }}</div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Usuários avaliadores</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white">{{ $totalAvaliadores }}</div>
                        </div>

                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Avaliações registradas</div>
                            <div class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white">{{ $totalAvaliações }}</div>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach ($personalData as $item)
                            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                                <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $item['label'] }}</div>
                                <div class="mt-2 text-sm text-gray-800 dark:text-gray-100">{{ $item['value'] }}</div>
                            </div>
                        @endforeach
                    </div>

                    <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                        <div class="mb-4">
                            <div class="text-base font-semibold text-gray-950 dark:text-white">MÃ©dia geral por critÃ©rio</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">Consolidado de todos os votos por categoria avaliada.</div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            @foreach ($criteriaAverages as $label => $value)
                                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950">
                                    <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $label }}</div>
                                    <div class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ $formatScore((float) $value) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-filament::section>
        </div>

        <div class="space-y-6">
            <x-filament::section
                heading="Comparativos de periodo"
                description="Acompanhe como a média atual se comporta em relacao ao periodo imédiatamente anterior."
            >
                <div class="grid gap-4 lg:grid-cols-3">
                    @foreach (['semanal', 'mensal', 'semestral'] as $comparisonKey)
                        @php($comparison = $periodComparisons[$comparisonKey])
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-base font-semibold text-gray-950 dark:text-white">{{ $comparison['label'] }}</div>
                            <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $comparison['current_label'] }} vs {{ $comparison['previous_label'] }}</div>

                            <div class="mt-4 space-y-4">
                                <div class="rounded-xl bg-amber-50 p-4 dark:bg-amber-500/10">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-amber-700 dark:text-amber-300">MÃ©dia das avaliaÃ§Ãµes</div>
                                    <div class="mt-2 flex items-end justify-between gap-3">
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Atual</div>
                                            <div class="text-xl font-semibold text-gray-950 dark:text-white">{{ $formatScore((float) $comparison['raw_current']) }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Anterior</div>
                                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $formatScore((float) $comparison['raw_previous']) }}</div>
                                            <div class="mt-1 text-xs font-semibold {{ $comparison['raw_delta'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $comparison['raw_delta'] >= 0 ? '+' : '' }}{{ number_format((float) $comparison['raw_delta'], 2, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-xl bg-teal-50 p-4 dark:bg-teal-500/10">
                                    <div class="text-xs font-semibold uppercase tracking-wide text-teal-700 dark:text-teal-300">MÃ©dia consolidada dos avaliadores</div>
                                    <div class="mt-2 flex items-end justify-between gap-3">
                                        <div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Atual</div>
                                            <div class="text-xl font-semibold text-gray-950 dark:text-white">{{ $formatScore((float) $comparison['consolidated_current']) }}</div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500 dark:text-gray-400">Anterior</div>
                                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $formatScore((float) $comparison['consolidated_previous']) }}</div>
                                            <div class="mt-1 text-xs font-semibold {{ $comparison['consolidated_delta'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $comparison['consolidated_delta'] >= 0 ? '+' : '' }}{{ number_format((float) $comparison['consolidated_delta'], 2, ',', '.') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-xl border border-gray-200 p-3 dark:border-gray-800">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Avaliações no periodo atual</div>
                                        <div class="mt-1 text-lg font-semibold text-gray-950 dark:text-white">{{ $comparison['current_total_evaluations'] }}</div>
                                    </div>
                                    <div class="rounded-xl border border-gray-200 p-3 dark:border-gray-800">
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Avaliações no periodo anterior</div>
                                        <div class="mt-1 text-lg font-semibold text-gray-950 dark:text-white">{{ $comparison['previous_total_evaluations'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>

            <x-filament::section
                heading="Resumo detalhado por avaliador"
                description="MÃ©dia individual, categorias votadas e histÃ³rico resumido de cada profissional."
            >
                <div class="space-y-4">
                    @forelse ($usuários as $item)
                        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="flex flex-col gap-4 border-b border-gray-200 bg-gray-50 p-5 dark:border-gray-800 dark:bg-gray-950/60 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-center gap-4">
                                    @if ($item['foto'])
                                        <img src="{{ $item['foto'] }}" alt="" class="h-14 w-14 rounded-full object-cover shadow-sm">
                                    @else
                                        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-lg font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                            {{ str($item['user']?->name ?? '?')->substr(0, 1)->upper() }}
                                        </div>
                                    @endif

                                    <div>
                                        <div class="text-lg font-semibold text-gray-950 dark:text-white">{{ $item['user']?->name ?? 'Usuário nao informado' }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item['user']?->email ?? 'Sem e-mail cadastrado' }}</div>
                                    </div>
                                </div>

                                <div class="grid gap-3 sm:grid-cols-3">
                                    <div class="rounded-xl bg-white px-4 py-3 text-center shadow-sm dark:bg-gray-900">
                                        <div class="text-xs uppercase tracking-wide text-gray-500">Qtd. votos</div>
                                        <div class="mt-1 text-xl font-semibold text-gray-950 dark:text-white">{{ $item['quantidade'] }}</div>
                                    </div>
                                    <div class="rounded-xl bg-white px-4 py-3 text-center shadow-sm dark:bg-gray-900">
                                        <div class="text-xs uppercase tracking-wide text-gray-500">MÃ©dia individual</div>
                                        <div class="mt-1 text-xl font-semibold text-gray-950 dark:text-white">{{ $formatScore((float) $item['média']) }}</div>
                                    </div>
                                    <div class="rounded-xl bg-white px-4 py-3 text-center shadow-sm dark:bg-gray-900">
                                        <div class="text-xs uppercase tracking-wide text-gray-500">Último voto</div>
                                        <div class="mt-1 text-sm font-semibold text-gray-950 dark:text-white">{{ $item['ultima_avaliação']?->created_at?->format('d/m/Y') ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4 p-5">
                                <div class="grid gap-4 lg:grid-cols-5">
                                    @foreach ($item['critérios'] as $label => $value)
                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-950">
                                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">{{ $label }}</div>
                                            <div class="mt-2 text-xl font-semibold text-gray-950 dark:text-white">{{ $formatScore((float) $value) }}</div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-800">
                                    <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-950 dark:text-gray-400">
                                            <tr>
                                                <th class="px-4 py-3">Data</th>
                                                <th class="px-4 py-3">Controle</th>
                                                <th class="px-4 py-3">Autonomia</th>
                                                <th class="px-4 py-3">TransparÃªncia</th>
                                                <th class="px-4 py-3">SuperaÃ§Ã£o</th>
                                                <th class="px-4 py-3">Autocuidado</th>
                                                <th class="px-4 py-3">MÃ©dia final</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                            @foreach ($item['avaliações'] as $avaliação)
                                                <tr class="text-gray-700 dark:text-gray-200">
                                                    <td class="px-4 py-3 whitespace-nowrap">{{ $avaliação->created_at?->format('d/m/Y') }}</td>
                                                    <td class="px-4 py-3">{{ $formatScore((float) $avaliação->controler) }}</td>
                                                    <td class="px-4 py-3">{{ $formatScore((float) $avaliação->autonomia) }}</td>
                                                    <td class="px-4 py-3">{{ $formatScore((float) $avaliação->transparencia) }}</td>
                                                    <td class="px-4 py-3">{{ $formatScore((float) $avaliação->superacao) }}</td>
                                                    <td class="px-4 py-3">{{ $formatScore((float) $avaliação->autocuidado) }}</td>
                                                    <td class="px-4 py-3 font-semibold">{{ $formatScore((float) $avaliação->Total) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-2xl border border-dashed border-gray-300 p-8 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
                            Nenhum avaliador com votos registrados para este acolhido.
                        </div>
                    @endforelse
                </div>
            </x-filament::section>

            <x-filament::section
                heading="HistÃ³rico cronolÃ³gico das avaliaÃ§Ãµes"
                description="Cada registro individual com os valores atribuÃ­dos por categoria."
            >
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                            <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-950 dark:text-gray-400">
                                <tr>
                                    <th class="px-4 py-3">Data</th>
                                    <th class="px-4 py-3">Avaliador</th>
                                    <th class="px-4 py-3">Controle</th>
                                    <th class="px-4 py-3">Autonomia</th>
                                    <th class="px-4 py-3">TransparÃªncia</th>
                                    <th class="px-4 py-3">SuperaÃ§Ã£o</th>
                                    <th class="px-4 py-3">Autocuidado</th>
                                    <th class="px-4 py-3">MÃ©dia final</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                @forelse ($avaliações as $avaliação)
                                    <tr class="text-gray-700 dark:text-gray-200">
                                        <td class="px-4 py-3 whitespace-nowrap">{{ $avaliação->created_at?->format('d/m/Y') }}</td>
                                        <td class="px-4 py-3">{{ $avaliação->user?->name ?? '-' }}</td>
                                        <td class="px-4 py-3">{{ $formatScore((float) $avaliação->controler) }}</td>
                                        <td class="px-4 py-3">{{ $formatScore((float) $avaliação->autonomia) }}</td>
                                        <td class="px-4 py-3">{{ $formatScore((float) $avaliação->transparencia) }}</td>
                                        <td class="px-4 py-3">{{ $formatScore((float) $avaliação->superacao) }}</td>
                                        <td class="px-4 py-3">{{ $formatScore((float) $avaliação->autocuidado) }}</td>
                                        <td class="px-4 py-3 font-semibold">{{ $formatScore((float) $avaliação->Total) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            Nenhuma avaliaÃ§Ã£o encontrada para este acolhido.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section
                heading="Logica de calculo das médias"
                description="ExplicaÃ§Ã£o objetiva de como o sistema consolida as notas apresentadas neste relatÃ³rio."
            >
                <div class="grid gap-4">
                    @foreach ($logicasMédias as $logica)
                        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                            <div class="text-base font-semibold text-gray-950 dark:text-white">{{ $logica['titulo'] }}</div>
                            <div class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300">{{ $logica['descricao'] }}</div>
                            <div class="mt-3 rounded-xl bg-amber-50 px-4 py-3 text-sm font-semibold text-amber-800 dark:bg-amber-500/10 dark:text-amber-300">{{ $logica['formula'] }}</div>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        </div>
    </div>
</x-filament-panels::page>


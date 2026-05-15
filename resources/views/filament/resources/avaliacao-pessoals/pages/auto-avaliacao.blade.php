<x-filament-panels::page>
    <div class="mx-auto max-w-7xl space-y-6">
        <x-filament::section
            heading="Auto Avaliacao"
            description="Relatorio de apoio para preenchimento manual das categorias de auto avaliacao dos acolhidos."
        >
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Total de acolhidos</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-950 dark:text-white">{{ $acolhidos->count() }}</div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Gerado em</div>
                    <div class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ $geradoEm->format('d/m/Y H:i') }}</div>
                </div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="text-sm text-gray-500 dark:text-gray-400">Formato do PDF</div>
                    <div class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">Paisagem A4</div>
                </div>
            </div>
        </x-filament::section>

        <x-filament::section
            heading="Previa da lista"
            description="As colunas de avaliacao sao mantidas em branco no PDF para preenchimento manual."
        >
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm dark:divide-gray-800">
                        <thead class="bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:bg-gray-950 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3">Matricula</th>
                                <th class="px-4 py-3">Nome do acolhido</th>
                                <th class="px-4 py-3">Dias na casa</th>
                                <th class="px-4 py-3">Controle</th>
                                <th class="px-4 py-3">Autonomia</th>
                                <th class="px-4 py-3">Transparencia</th>
                                <th class="px-4 py-3">Superacao</th>
                                <th class="px-4 py-3">AutoCuidado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            @forelse ($acolhidos as $acolhido)
                                <tr class="text-gray-700 dark:text-gray-200">
                                    <td class="px-4 py-3">{{ $acolhido['matricula'] }}</td>
                                    <td class="px-4 py-3">{{ $acolhido['nome'] }}</td>
                                    <td class="px-4 py-3">{{ $acolhido['dias_na_casa'] }}</td>
                                    <td class="px-4 py-3 text-gray-300">_____</td>
                                    <td class="px-4 py-3 text-gray-300">_____</td>
                                    <td class="px-4 py-3 text-gray-300">_____</td>
                                    <td class="px-4 py-3 text-gray-300">_____</td>
                                    <td class="px-4 py-3 text-gray-300">_____</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        Nenhum acolhido cadastrado no sistema.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>

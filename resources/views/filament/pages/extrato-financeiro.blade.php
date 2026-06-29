<x-filament::page>
    <div class="space-y-6">
        <x-filament::section>
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                {{ $this->form }}
            </div>
            <div class="mt-4 flex flex-wrap gap-3">
                <x-filament::input.wrapper class="w-full md:w-96">
                    <x-filament::input
                        type="search"
                        wire:model.live.debounce.400ms="data.search"
                        placeholder="Pesquisar por empresa, descrição, observações, valor, responsável..."
                    />
                </x-filament::input.wrapper>
            </div>
        </x-filament::section>

        @if (! $this->acolhido)
            <x-filament::section>
                <div class="py-10 text-center text-sm text-gray-500">
                    Selecione um acolhido para carregar o extrato financeiro.
                </div>
            </x-filament::section>
        @else
            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ([
                    ['Saldo Atual', $this->summary['saldo_atual'], 'primary'],
                    ['Total Recebido', $this->summary['total_recebido'], 'success'],
                    ['Total Sacado', $this->summary['total_sacado'], 'danger'],
                    ['Compras Internas', $this->summary['compras_internas'], 'warning'],
                    ['Transferências Família', $this->summary['transferencias_familia'], 'gray'],
                    ['Desconto logístico', $this->summary['retido_cerape'], 'info'],
                    ['Saldo Disponível', $this->summary['saldo_disponivel'], 'primary'],
                    ['Última Movimentação', $this->summary['ultima_movimentacao'], 'gray'],
                ] as [$label, $value, $color])
                    <x-filament::section>
                        <div class="text-xs uppercase tracking-wide text-gray-500">{{ $label }}</div>
                        <div class="mt-2 text-2xl font-semibold text-{{ $color }}-600">
                            {{ is_numeric($value) ? 'R$ ' . number_format((float) $value, 2, ',', '.') : $value }}
                        </div>
                    </x-filament::section>
                @endforeach
            </div>

            <x-filament::section>
                <div class="space-y-3">
                    <div class="text-sm font-medium text-gray-600">Timeline</div>
                    <div class="space-y-3">
                        @forelse ($this->entries->sortByDesc('data')->take(10) as $entry)
                            <div class="flex items-start gap-4 rounded-xl border border-gray-200 p-4">
                                <div class="min-w-20 text-sm font-semibold text-gray-600">{{ $entry->data->format('d/m') }}</div>
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="font-medium">{{ $entry->tipo }}</span>
                                        <span class="text-sm text-gray-500">{{ $entry->descricao }}</span>
                                        <span class="rounded-full bg-gray-100 px-2 py-1 text-xs">{{ $entry->situacao }}</span>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $entry->empresa ?? '-' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold {{ $entry->credito > 0 ? 'text-success-600' : 'text-danger-600' }}">
                                        {{ $entry->credito > 0 ? '+' : '-' }}R$ {{ number_format(max($entry->credito, $entry->debito), 2, ',', '.') }}
                                    </div>
                                    <div class="text-sm text-blue-600">Saldo R$ {{ number_format($entry->saldoAposLancamento, 2, ',', '.') }}</div>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-sm text-gray-500">
                                Nenhuma movimentação encontrada para os filtros selecionados.
                            </div>
                        @endforelse
                    </div>
                </div>
            </x-filament::section>

            <x-filament::section>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium">Data</th>
                                <th class="px-3 py-2 text-left font-medium">Tipo</th>
                                <th class="px-3 py-2 text-left font-medium">Descrição</th>
                                <th class="px-3 py-2 text-left font-medium">Empresa</th>
                                <th class="px-3 py-2 text-right font-medium">Crédito</th>
                                <th class="px-3 py-2 text-right font-medium">Débito</th>
                                <th class="px-3 py-2 text-right font-medium">Saldo após lançamento</th>
                                <th class="px-3 py-2 text-left font-medium">Responsável</th>
                                <th class="px-3 py-2 text-left font-medium">Observações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse ($this->entries as $entry)
                                <tr>
                                    <td class="px-3 py-2">{{ $entry->data->format('d/m/Y') }}</td>
                                    <td class="px-3 py-2">
                                        <span class="rounded-full px-2 py-1 text-xs font-medium
                                            {{ $entry->debito > 0 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                            {{ $entry->tipo }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2">{{ $entry->descricao }}</td>
                                    <td class="px-3 py-2">{{ $entry->empresa ?? '-' }}</td>
                                    <td class="px-3 py-2 text-right text-green-700">{{ $entry->credito > 0 ? 'R$ ' . number_format($entry->credito, 2, ',', '.') : '-' }}</td>
                                    <td class="px-3 py-2 text-right text-red-700">{{ $entry->debito > 0 ? 'R$ ' . number_format($entry->debito, 2, ',', '.') : '-' }}</td>
                                    <td class="px-3 py-2 text-right font-medium text-blue-700">R$ {{ number_format($entry->saldoAposLancamento, 2, ',', '.') }}</td>
                                    <td class="px-3 py-2">{{ $entry->responsavel ?? '-' }}</td>
                                    <td class="px-3 py-2">{{ $entry->observacoes ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-3 py-8 text-center text-gray-500">
                                        Nenhum registro encontrado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament::page>

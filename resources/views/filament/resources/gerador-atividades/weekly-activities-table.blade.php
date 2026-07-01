@php
    $record = $getRecord();
    $rows = \App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource::plannedActivities($record);
    $acolhidos = \App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource::acolhidoNames($record?->acolhidos_ids);
    $periodo = \App\Filament\Resources\GeradorAtividades\GeradorAtividadeResource::getPeriodLabel($record);
@endphp

<div class="space-y-4">
    <div class="grid gap-4 md:grid-cols-1">
        <div class="rounded-2xl border border-primary-100 bg-gradient-to-br from-primary-50 to-white p-4 shadow-sm">
            <div class="text-xs font-semibold uppercase tracking-[0.18em] text-primary-700">Periodo</div>
            <div class="mt-2 text-base font-semibold text-gray-900">{{ $periodo }}</div>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-600">Ordem</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-600">Atividades praticas</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-600">Demanda</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-[0.18em] text-gray-600">Acolhidos</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($rows as $row)
                    <tr class="align-top">
                        <td class="whitespace-nowrap px-4 py-4 font-semibold text-gray-700">{{ $row['ordem'] }}</td>
                        <td class="px-4 py-4 font-medium text-gray-900">
                            {{ filled($row['atividade_pratica'] ?? []) ? implode(', ', $row['atividade_pratica']) : '-' }}
                        </td>
                        <td class="px-4 py-4 text-gray-700">
                            <div class="prose prose-sm max-w-none prose-p:my-1 prose-li:my-0.5">
                                {!! $row['demanda_html'] ?: '<span class="text-gray-400">-</span>' !!}
                            </div>
                        </td>
                        <td class="px-4 py-4 text-gray-700">
                            @if ($row['acolhidos_nomes'] === [])
                                <span class="text-gray-400">-</span>
                            @else
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($row['acolhidos_nomes'] as $nome)
                                        <span class="rounded-full bg-primary-50 px-3 py-1 text-xs font-medium text-primary-700 ring-1 ring-primary-200">
                                            {{ $nome }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">
                            Nenhuma atividade cadastrada para este quadro semanal.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
</div>

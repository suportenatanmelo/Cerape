@extends('pdf.layouts.cerape')

@section('title', 'Extrato Financeiro CERAPE')

@section('styles')
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
    .header { border-bottom: 2px solid #0f766e; margin-bottom: 16px; padding-bottom: 10px; }
    .title { font-size: 18px; font-weight: bold; margin: 0 0 4px; }
    .muted { color: #6b7280; margin: 0; font-size: 10px; }
    .grid { display: table; width: 100%; border-spacing: 8px; margin-bottom: 16px; }
    .card { display: table-cell; width: 25%; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px; font-size: 10px; }
    .card strong { display: block; margin-bottom: 6px; font-size: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #e5e7eb; padding: 6px; vertical-align: top; font-size: 10px; }
    th { background: #f3f4f6; font-weight: 700; }
    .green { color: #047857; }
    .red { color: #b91c1c; }
</style>
@endsection

@section('content')
    <div class="header report-header">
        <div class="title">CERAPE - Extrato Financeiro</div>
        <div class="muted">{{ $acolhido->nome_completo_paciente }}</div>
        <div class="muted">Emitido em {{ $printedAt->format('d/m/Y H:i') }} por {{ $printedBy?->name ?? 'Sistema' }}</div>
    </div>

    <div class="grid">
        <div class="card"><strong>Saldo atual</strong>R$ {{ number_format((float) ($summary['saldo_atual'] ?? 0), 2, ',', '.') }}</div>
        <div class="card"><strong>Total recebido</strong>R$ {{ number_format((float) ($summary['total_recebido'] ?? 0), 2, ',', '.') }}</div>
        <div class="card"><strong>Total sacado</strong>R$ {{ number_format((float) ($summary['total_sacado'] ?? 0), 2, ',', '.') }}</div>
        <div class="card"><strong>Desconto logístico</strong>R$ {{ number_format((float) ($summary['retido_cerape'] ?? 0), 2, ',', '.') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Tipo</th>
                <th>Descrição</th>
                <th>Empresa</th>
                <th>Crédito</th>
                <th>Débito</th>
                <th>Saldo após</th>
                <th>Responsável</th>
                <th>Observações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($entries as $entry)
                <tr>
                    <td>{{ $entry->data->format('d/m/Y') }}</td>
                    <td>{{ $entry->tipo }}</td>
                    <td>{{ $entry->descricao }}</td>
                    <td>{{ $entry->empresa ?? '-' }}</td>
                    <td class="green">{{ $entry->credito > 0 ? 'R$ ' . number_format($entry->credito, 2, ',', '.') : '-' }}</td>
                    <td class="red">{{ $entry->debito > 0 ? 'R$ ' . number_format($entry->debito, 2, ',', '.') : '-' }}</td>
                    <td>R$ {{ number_format($entry->saldoAposLancamento, 2, ',', '.') }}</td>
                    <td>{{ $entry->responsavel ?? '-' }}</td>
                    <td>{{ $entry->observacoes ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection


@extends('pdf.layouts.cerape')

@section('title', 'Relatorio de substancia psicoativa')

@section('styles')
<style>
    body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.45; margin: 0; }
    .report-header { margin-bottom: 12px; }
    .report-eyebrow { font-size: 9px; }
    .report-title { font-size: 16px; margin: 2px 0 3px; }
    .report-subtitle { font-size: 9px; margin-bottom: 2px; }
    table { border-collapse: collapse; width: 100%; }
    th { background: #f9fafb; color: #4b5563; font-size: 10px; text-align: left; text-transform: uppercase; width: 34%; }
    th, td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
    .section { page-break-inside: avoid; }
</style>
@endsection

@section('content')
    <div class="report-header">
        <div class="report-eyebrow">Saúde</div>
        <h1 class="report-title report-title--compact">Relatorio de substancia psicoativa</h1>
        <div class="report-subtitle"><strong>{{ $record->acolhido?->nome_completo_paciente ?? 'Acolhido não informado' }}</strong></div>
        <div class="report-subtitle">Substancias: {{ $formatValue($record->nome) }}</div>
        <div class="report-subtitle">Emitido em: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    @foreach ($sections as $title => $fields)
        <div class="section">
            <h2 class="report-section-title">{{ $title }}</h2>
            <table>
                <tbody>
                    @foreach ($fields as $label => $value)
                        <tr>
                            <th>{{ $label }}</th>
                            <td>{{ $formatValue($value) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach
@endsection


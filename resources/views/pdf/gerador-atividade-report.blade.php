@extends('pdf.layouts.cerape')

@section('title', 'Quadro semanal de atividades')

@section('content')
    <style>
        @page { size: A4 landscape; margin: 5mm; }
        html, body { width: 100%; }
        body { font-size: 9px; line-height: 1.2; }
        .page { width: 100%; }
        .report-header { margin-bottom: 8px; }
        .report-eyebrow { font-size: 9px; }
        .report-title { font-size: 16px; margin: 2px 0 3px; }
        .report-subtitle { font-size: 8px; margin-bottom: 0; }
        .title { border: 1px solid #9ca3af; border-bottom: 0; font-size: 16px; font-weight: bold; padding: 6px 8px; text-align: center; text-transform: uppercase; }
        .meta { border: 1px solid #9ca3af; border-bottom: 0; padding: 5px 8px; }
        .meta-line { margin-bottom: 2px; font-size: 8px; }
        .meta-line:last-child { margin-bottom: 0; }
        .compact-note { margin-top: 4px; font-size: 8px; color: #6b7280; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #9ca3af; padding: 5px 7px; vertical-align: top; }
        th { background: #f3f4f6; font-size: 7px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.01em; }
        td { font-size: 8px; }
        td, th { overflow-wrap: normal; word-break: normal; }
        td { line-height: 1.25; }
        .col-order { text-align: center; width: 4%; }
        .col-activity { width: 20%; }
        .col-demand { width: 54%; }
        .col-names { width: 22%; }
        .demand p { margin: 0 0 2px; }
        .demand ul, .demand ol { margin: 1px 0 0 14px; padding: 0; }
        .demand li { margin-bottom: 2px; }
        .names-list { margin: 0; padding-left: 14px; }
        .names-list li { margin-bottom: 0; }
        .observacoes { border: 1px solid #9ca3af; border-top: 0; padding: 8px 10px; page-break-inside: avoid; }
        .observacoes h2 { font-size: 8px; margin: 0 0 3px; text-transform: uppercase; }
        tr { page-break-inside: avoid; }
    </style>

    <div class="report-header">
        <div class="report-eyebrow">Atividades</div>
        <div class="report-title report-title--compact">{{ $record->titulo }} <span>- {{ $periodoLabel }}</span></div>
        <div class="report-subtitle">Documento de programação semanal e demanda operacional</div>
    </div>
    <div class="meta">
        <div class="meta-line"><strong>Responsavel:</strong> {{ $record->user?->name ?? '-' }}</div>
        <div class="meta-line"><strong>Emitido em:</strong> {{ now()->format('d/m/Y H:i') }}</div>
        <div class="compact-note">Versao compacta para reduzir numero de folhas.</div>
    </div>

    <table>
        <thead>
            <tr>
                <th class="col-order">Ordem</th>
                <th class="col-activity">Atividades praticas</th>
                <th class="col-demand">Demanda</th>
                <th class="col-names">Nome</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($atividadesPlanejadas as $atividade)
                <tr>
                    <td class="col-order">{{ $atividade['ordem'] }}</td>
                    <td class="col-activity">{{ filled($atividade['atividade_pratica'] ?? []) ? implode(', ', $atividade['atividade_pratica']) : '-' }}</td>
                    <td class="col-demand demand">{!! $atividade['demanda_html'] ?: '-' !!}</td>
                    <td class="col-names">
                        @if ($atividade['acolhidos_nomes'] === [])
                            -
                        @else
                            <ul class="names-list">
                                @foreach ($atividade['acolhidos_nomes'] as $nome)
                                    <li>{{ $nome }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Nenhuma atividade cadastrada para este periodo.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="observacoes">
        <h2 class="report-section-title">Observacoes complementares</h2>
        {!! filled($record->observacoes) ? nl2br(e($record->observacoes)) : 'Sem observacoes adicionais.' !!}
    </div>
@endsection


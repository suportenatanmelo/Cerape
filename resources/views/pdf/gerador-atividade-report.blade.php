@extends('pdf.layout')

@section('title', 'Quadro semanal de atividades')

@section('content')
    <style>
        .title { border: 1px solid #9ca3af; border-bottom: 0; font-size: 20px; font-weight: bold; padding: 10px 14px; text-align: center; text-transform: uppercase; }
        .meta { border: 1px solid #9ca3af; border-bottom: 0; padding: 8px 12px; }
        .meta-line { margin-bottom: 3px; }
        .meta-line:last-child { margin-bottom: 0; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #9ca3af; padding: 6px 7px; vertical-align: top; }
        th { background: #f3f4f6; font-size: 10px; font-weight: bold; text-transform: uppercase; }
        .col-order { text-align: center; width: 7%; }
        .col-activity { width: 26%; }
        .col-demand { width: 47%; }
        .col-names { width: 20%; }
        .demand p { margin: 0 0 5px; }
        .demand ul, .demand ol { margin: 4px 0 0 16px; padding: 0; }
        .demand li { margin-bottom: 3px; }
        .names-list { margin: 0; padding-left: 16px; }
        .names-list li { margin-bottom: 2px; }
        .observacoes { border: 1px solid #9ca3af; border-top: 0; padding: 10px 12px; }
        .observacoes h2 { font-size: 11px; margin: 0 0 6px; text-transform: uppercase; }
    </style>

    <div class="report-header">
        <div class="report-eyebrow">Atividades</div>
        <div class="report-title report-title--compact">{{ $record->titulo }} <span>- {{ $periodoLabel }}</span></div>
        <div class="report-subtitle">Documento de programação semanal e demanda operacional</div>
    </div>
    <div class="meta">
        <div class="meta-line"><strong>Responsavel:</strong> {{ $record->user?->name ?? '-' }}</div>
        <div class="meta-line"><strong>Emitido em:</strong> {{ now()->format('d/m/Y H:i') }}</div>
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
                    <td class="col-activity">{{ $atividade['atividade_pratica'] ?: '-' }}</td>
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

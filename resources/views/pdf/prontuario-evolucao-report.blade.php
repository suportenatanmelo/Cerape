@extends('pdf.layout')

@section('title', 'Relatorio de prontuario de evolucao')

@section('content')
    <style>
        .hero { display: table; padding-bottom: 18px; width: 100%; }
        .photo-wrap { display: table-cell; vertical-align: top; width: 126px; }
        .photo-empty { background: #f3f4f6; border: 2px solid #d1d5db; border-radius: 14px; color: #6b7280; height: 156px; line-height: 156px; text-align: center; width: 116px; }
        .hero-content { display: table-cell; padding-left: 16px; vertical-align: top; }
        .grid { margin-top: 16px; width: 100%; }
        .grid-item { border: 1px solid #e5e7eb; display: inline-block; margin: 0 8px 8px 0; min-height: 54px; padding: 8px 10px; vertical-align: top; width: 31.5%; }
        .grid-label { color: #6b7280; font-size: 9px; font-weight: bold; margin-bottom: 4px; text-transform: uppercase; }
        .grid-value { color: #111827; font-size: 11px; }
        .section { page-break-inside: avoid; }
        .clinical-box { border: 1px solid #d1d5db; border-radius: 14px; margin-top: 8px; padding: 18px; }
        .clinical-box p { margin: 0 0 10px; }
        .clinical-box img { border-radius: 12px; display: block; height: auto; margin: 10px 0; max-width: 100%; }
        .clinical-box ul, .clinical-box ol { margin: 8px 0 8px 22px; padding: 0; }
        .clinical-box table { border-collapse: collapse; margin-top: 10px; width: 100%; }
        .clinical-box th, .clinical-box td { border: 1px solid #e5e7eb; padding: 6px; vertical-align: top; }
    </style>

    <div class="hero">
        <div class="photo-wrap">
            <div class="photo-empty">Sem foto</div>
        </div>
        <div class="hero-content">
            <div class="report-eyebrow">Prontuário</div>
            <h1 class="report-title report-title--compact">Relatorio de prontuario de evolucao</h1>
            <div class="report-subtitle"><strong>{{ $acolhido?->nome_completo_paciente ?? 'Acolhido nao identificado' }}</strong></div>
            <div class="report-subtitle">Prontuario registrado em: {{ $record->data_prontuario?->format('d/m/Y H:i') ?? '-' }}</div>
            <div class="report-subtitle">Proxima data do prontuario: {{ $proximaDataProntuario ?? '-' }}</div>
            <div class="report-subtitle">Atividades realizadas: {{ $atividadeLabel ?? '-' }}</div>
            <div class="report-subtitle">Registrado por: {{ $record->user?->name ?? '-' }}</div>
            <div class="report-subtitle">Emitido em: {{ now()->format('d/m/Y H:i') }}</div>
            <span class="report-badge report-badge--teal">Evolucao clinica estruturada</span>
        </div>
    </div>

    <div class="grid">
        @foreach ($personalData as $item)
            <div class="grid-item">
                <div class="grid-label">{{ $item['label'] }}</div>
                <div class="grid-value">{{ $item['value'] }}</div>
            </div>
        @endforeach
    </div>

    <div class="section">
        <h2 class="report-section-title">Evolucao registrada</h2>
        <div class="clinical-box">{!! $conteudoHtml !!}</div>
    </div>
@endsection

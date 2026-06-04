@extends('pdf.layout')

@section('title')
Documento de prontuário de evolução
@endsection

@section('styles')
<style>
        * { box-sizing: border-box; }
        body { background: #ffffff; color: #0f172a; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.5; margin: 0; }
        .page { padding: 22px; }
        .brand-bar { border-bottom: 1px solid #dbe4ea; margin-bottom: 16px; padding-bottom: 12px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 60px; max-width: 220px; }
        .hero { background: #f8fafc; border: 1px solid #dbe4ea; border-radius: 16px; color: #0f172a; display: table; margin-bottom: 14px; padding: 18px; width: 100%; }
        .photo-wrap { display: table-cell; vertical-align: top; width: 126px; }
        .photo { border: 3px solid #cbd5e1; border-radius: 14px; height: 156px; object-fit: cover; width: 116px; }
        .photo-empty { background: #e2e8f0; border: 2px dashed #94a3b8; border-radius: 14px; color: #334155; height: 156px; line-height: 156px; text-align: center; width: 116px; }
        .hero-content { display: table-cell; padding-left: 16px; vertical-align: top; }
        h1 { font-size: 20px; margin: 0 0 6px; }
        h2 { color: #1d4ed8; font-size: 14px; margin: 22px 0 10px; }
        .muted { color: #475569; }
        .pill { background: #dcfce7; border-radius: 999px; color: #166534; display: inline-block; font-size: 10px; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        .stars { display: inline-block; margin-right: 6px; }
        .star-on { color: #f59e0b; font-size: 15px; }
        .star-off { color: #cbd5e1; font-size: 15px; }
        .grid { margin-top: 16px; width: 100%; }
        .grid-item { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; display: inline-block; margin: 0 8px 8px 0; min-height: 54px; padding: 8px 10px; vertical-align: top; width: 31.5%; }
        .grid-label { color: #6b7280; font-size: 9px; font-weight: bold; margin-bottom: 4px; text-transform: uppercase; }
        .grid-value { color: #111827; font-size: 11px; }
        .section { page-break-inside: avoid; }
        .clinical-box { background: #fff; border: 1px solid #dbe4ea; border-radius: 14px; margin-top: 8px; padding: 18px; }
        .clinical-box p { margin: 0 0 10px; }
        .clinical-box img { border-radius: 12px; display: block; height: auto; margin: 10px 0; max-width: 100%; }
        .clinical-box ul, .clinical-box ol { margin: 8px 0 8px 22px; padding: 0; }
        .clinical-box table { border-collapse: collapse; margin-top: 10px; width: 100%; }
        .clinical-box th, .clinical-box td { border: 1px solid #dbe4ea; padding: 6px; vertical-align: top; }
        .footer { border-top: 1px solid #dbe4ea; color: #64748b; font-size: 9px; margin-top: 24px; padding-top: 8px; text-align: center; }
    </style>
@endsection

@section('content')
<div class="hero">
            <div class="photo-wrap">
                @if ($fotoAcolhido)
                    <img src="{{ $fotoAcolhido }}" class="photo" alt="">
                @else
                    <div class="photo-empty">Sem foto</div>
                @endif
            </div>
            <div class="hero-content">
                <h1>Documento de prontuário de evolução</h1>
                <div><strong>{{ $acolhido?->nome_completo_paciente ?? 'Acolhido não identificado' }}</strong></div>
                <div class="muted">Prontuario registrado em: {{ $record->data_prontuario?->format('d/m/Y H:i') ?? '-' }}</div>
                <div class="muted">Proxima data do prontuario: {{ $proximaDataProntuario ?? '-' }}</div>
                <div class="muted">Atividades realizadas: {{ $atividadeLabel ?? '-' }}</div>
                <div class="muted">Responsável pela informação: {{ $record->user?->name ?? '-' }}</div>
                <div class="muted">Função do responsável: {{ $record->funcao_responsavel_informacao ?? '-' }}</div>
                <div class="muted">
                    Nota de elogio:
                    <span class="stars">
                        @foreach ($notaElogioStars as $filled)
                            <span class="{{ $filled ? 'star-on' : 'star-off' }}">★</span>
                        @endforeach
                    </span>
                    {{ $notaElogioLabel ?? '-' }}
                </div>
                <div class="muted">Emitido em: {{ now()->format('d/m/Y H:i') }}</div>
                <div class="pill">Evolucao clinica estruturada</div>
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
            <h2>Evolucao registrada</h2>
            <div class="clinical-box">{!! $conteudoHtml !!}</div>
        </div>

@endsection

@extends('pdf.layout')

@section('title', $record->titulo)

@section('content')
    <style>
        .meta { margin: 16px 0 20px; width: 100%; }
        .meta td { border: 1px solid #e5e7eb; padding: 8px 10px; vertical-align: top; }
        .meta .label { background: #f8fafc; color: #475569; font-weight: bold; width: 26%; }
        .box { border: 1px solid #dbe4ea; border-radius: 8px; padding: 14px; }
        .content p { margin: 0 0 10px; }
        .content ul, .content ol { margin: 0 0 12px 18px; padding: 0; }
    </style>

    <div class="report-header">
        <div class="report-eyebrow">Registro institucional</div>
        <h1 class="report-title report-title--compact">Ata de reunião</h1>
        <div class="report-subtitle"><strong>{{ $record->titulo }}</strong></div>
        <div class="report-subtitle">Documento gerado em {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table class="meta">
        <tr>
            <td class="label">Título</td>
            <td>{{ $record->titulo }}</td>
        </tr>
        <tr>
            <td class="label">Responsável pelo registro</td>
            <td>{{ $responsavel }}</td>
        </tr>
        <tr>
            <td class="label">Data e hora da reunião</td>
            <td>{{ $record->data_reuniao?->format('d/m/Y H:i') ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Descrição</td>
            <td>{{ filled($descricao) ? $descricao : '-' }}</td>
        </tr>
    </table>

    <div class="report-section-title">Conteúdo da ata</div>
    <div class="box content">
        {!! $conteudoHtml !!}
    </div>
@endsection

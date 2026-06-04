@extends('pdf.layout')

@section('title')
Documento de substância psicoativa
@endsection

@section('styles')
<style>
        * { box-sizing: border-box; }
        body { background: #ffffff; color: #0f172a; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.5; margin: 0; }
        .page { padding: 22px; }
        .brand-bar { border-bottom: 1px solid #dbe4ea; margin-bottom: 16px; padding-bottom: 12px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 62px; max-width: 220px; }
        .header { background: #f8fafc; border: 1px solid #dbe4ea; border-radius: 16px; padding: 16px 18px; width: 100%; }
        h1 { font-size: 22px; margin: 0 0 6px; }
        h2 { background: #eff6ff; border-bottom: 1px solid #bfdbfe; color: #1d4ed8; font-size: 13px; margin: 0; padding: 10px 12px; }
        .muted { color: #475569; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f8fafc; color: #475569; font-size: 10px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #dbe4ea; padding: 7px; vertical-align: top; }
        .section { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; margin-bottom: 12px; overflow: hidden; page-break-inside: avoid; }
        .section table { border-left: 0; border-right: 0; }
        .footer { border-top: 1px solid #dbe4ea; color: #64748b; font-size: 10px; margin-top: 22px; padding-top: 10px; text-align: center; }
    </style>
@endsection

@section('content')
<div class="header">
            <h1>Documento de substância psicoativa</h1>
            <div><strong>{{ $record->acolhido?->nome_completo_paciente ?? 'Acolhido nao informado' }}</strong></div>
            <div class="muted">Substancias: {{ $formatValue($record->nome) }}</div>
            <div class="muted">Emitido em: {{ now()->format('d/m/Y H:i') }}</div>
        </div>

        @foreach ($sections as $title => $fields)
            <div class="section">
                <h2>{{ $title }}</h2>
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

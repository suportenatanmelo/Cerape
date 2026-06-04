@extends('pdf.layout')

@section('title')
Documento de autoavaliação
@endsection

@section('styles')
<style>
        * { box-sizing: border-box; }
        body { background: #f5f7fb; color: #0f172a; font-family: DejaVu Sans, sans-serif; font-size: 10px; line-height: 1.4; margin: 0; }
        .page { padding: 20px; }
        .brand-bar { border-bottom: 1px solid #dbe4ea; margin-bottom: 14px; padding-bottom: 10px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 58px; max-width: 220px; }
        .header { background: #fff; border: 1px solid #dbe4ea; border-radius: 14px; padding: 14px 16px; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .muted { color: #6b7280; }
        .highlight { background: #ccfbf1; border-radius: 999px; color: #115e59; display: inline-block; font-size: 9px; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        table { border-collapse: collapse; margin-top: 16px; width: 100%; }
        th { background: #f8fafc; color: #334155; font-size: 8px; text-align: left; text-transform: uppercase; }
        th, td { border: 1px solid #dbe4ea; padding: 8px 6px; vertical-align: middle; }
        .blank-cell { background: #fff; height: 30px; }
        .footer { border-top: 1px solid #dbe4ea; color: #64748b; font-size: 9px; margin-top: 16px; padding-top: 8px; text-align: center; }
    </style>
@endsection

@section('content')
<div class="header">
            <h1>Autoavaliação dos acolhidos</h1>
            <div class="muted">Documento de apoio para preenchimento manual da equipe.</div>
            <div class="muted">Gerado em: {{ $geradoEm->format('d/m/Y H:i') }}</div>
            <div class="muted">Total de acolhidos listados: {{ $acolhidos->count() }}</div>
            <div class="highlight">Campos organizados para registro rapido</div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Matricula</th>
                    <th style="width: 30%;">Nome do acolhido</th>
                    <th style="width: 10%;">Dias na casa</th>
                    <th style="width: 13%;">Controle</th>
                    <th style="width: 13%;">Autonomia</th>
                    <th style="width: 13%;">Transparencia</th>
                    <th style="width: 13%;">Superacao</th>
                    <th style="width: 13%;">Autocuidado</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($acolhidos as $acolhido)
                    <tr>
                        <td>{{ $acolhido['matricula'] }}</td>
                        <td>{{ $acolhido['nome'] }}</td>
                        <td>{{ $acolhido['dias_na_casa'] }}</td>
                        <td class="blank-cell"></td>
                        <td class="blank-cell"></td>
                        <td class="blank-cell"></td>
                        <td class="blank-cell"></td>
                        <td class="blank-cell"></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">Nenhum acolhido cadastrado no sistema.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

@endsection

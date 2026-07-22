@extends('pdf.layouts.cerape')

@section('title', 'Relatorio de auto avaliacao')

@section('content')
    <style>
        .header { border-bottom: 2px solid #0f766e; padding-bottom: 12px; }
        table { border-collapse: collapse; margin-top: 16px; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 8px; text-align: left; text-transform: uppercase; }
        th, td { border: 1px solid #d1d5db; padding: 8px 6px; vertical-align: middle; }
        .blank-cell { height: 28px; }
    </style>

    <div class="report-header">
        <div class="report-eyebrow">Acompanhamento</div>
        <h1 class="report-title report-title--compact">Auto Avaliacao dos acolhidos</h1>
        <div class="report-subtitle">Relatorio em formato de apoio para preenchimento manual da equipe.</div>
        <div class="report-subtitle">Gerado em: {{ $geradoEm->format('d/m/Y H:i') }}</div>
        <div class="report-subtitle">Total de acolhidos listados: {{ $acolhidos->count() }}</div>
        <span class="report-badge report-badge--teal">Categorias em branco para preenchimento manual</span>
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
                <th style="width: 13%;">AutoCuidado</th>
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


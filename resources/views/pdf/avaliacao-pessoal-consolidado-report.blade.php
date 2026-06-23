@extends('pdf.layout')

@section('title', 'Relatorio consolidado de avaliacao pessoal')

@section('content')
    <style>
        .hero { background: linear-gradient(135deg, #0f766e, #134e4a); border-radius: 14px; color: #fff; margin-bottom: 12px; padding: 16px; }
        .cards { margin: 0 0 12px; width: 100%; }
        .cards td { padding: 0 5px; vertical-align: top; width: 25%; }
        .card { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; min-height: 74px; padding: 10px; }
        .card-label { color: #64748b; display: block; font-size: 8px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; }
        .card-value { color: #0f172a; display: block; font-size: 14px; font-weight: bold; margin-top: 5px; }
        .card-note { color: #475569; display: block; font-size: 8px; margin-top: 4px; }
        .panel { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; overflow: hidden; }
        table { border-collapse: separate; border-spacing: 0; width: 100%; }
        th { background: #ecfeff; color: #0f172a; font-size: 8px; font-weight: bold; letter-spacing: 0.08em; padding: 7px; text-align: left; text-transform: uppercase; }
        td { border-bottom: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        tbody tr:nth-child(even) td { background: #fafcfc; }
        tbody tr:last-child td { border-bottom: none; }
        .center { text-align: center; }
        .name { font-size: 9px; font-weight: bold; }
        .muted { color: #64748b; font-size: 8px; }
        .score { color: #0f766e; font-weight: bold; }
        .chip { background: #e2e8f0; border-radius: 999px; color: #334155; display: inline-block; font-size: 8px; font-weight: bold; padding: 3px 8px; }
        .formula-box { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; margin-top: 12px; padding: 10px 12px; }
        .formula-title { color: #0f172a; font-size: 9px; font-weight: bold; margin-bottom: 6px; text-transform: uppercase; }
        .formula-line { color: #334155; font-size: 8px; margin: 3px 0; }
    </style>

    <div class="hero">
        <div class="report-eyebrow" style="color: #d1fae5;">Avaliacao pessoal consolidada</div>
        <div class="report-title report-title--compact" style="color: #fff;">Relatorio geral de acolhidos avaliados</div>
        <div class="report-subtitle" style="color: rgba(255,255,255,0.92);">Gerado em: {{ $generatedAt->format('d/m/Y H:i') }}</div>
        @if ($selectedDate)
            <div class="report-subtitle" style="color: rgba(255,255,255,0.92);">Filtro aplicado: {{ $selectedDate->format('d/m/Y') }}</div>
        @else
            <div class="report-subtitle" style="color: rgba(255,255,255,0.92);">Periodo analisado: historico completo das avaliacoes registradas.</div>
        @endif
        <div class="report-subtitle" style="color: rgba(255,255,255,0.92);">O documento mostra todos os acolhidos que receberam votos, a quantidade total de votos e a media consolidada dos usuarios que avaliaram.</div>
    </div>

    <table class="cards">
        <tr>
            <td><div class="card"><span class="card-label">Acolhidos avaliados</span><span class="card-value">{{ $totalAcolhidos }}</span><span class="card-note">Cadastros com pelo menos uma avaliacao.</span></div></td>
            <td><div class="card"><span class="card-label">Usuarios que votaram</span><span class="card-value">{{ $totalProfissionais }}</span><span class="card-note">Avaliadores unicos considerados no relatorio.</span></div></td>
            <td><div class="card"><span class="card-label">Quantidade geral de votos</span><span class="card-value">{{ $totalVotos }}</span><span class="card-note">Total de lancamentos usados no calculo.</span></div></td>
            <td><div class="card"><span class="card-label">Media geral consolidada</span><span class="card-value">{{ $formatScore((float) $overallMediaUsuarios) }}</span><span class="card-note">Media final baseada nas medias dos usuarios que votaram.</span></div></td>
        </tr>
    </table>

    <div class="report-section-title">Consolidado por acolhido</div>
    <div class="panel">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">#</th>
                    <th style="width: 23%;">Acolhido</th>
                    <th style="width: 20%;">Usuarios que votaram</th>
                    <th style="width: 11%;">Votos</th>
                    <th style="width: 14%;">Media dos votos</th>
                    <th style="width: 14%;">Media de todos</th>
                    <th style="width: 13%;">Formula</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rows as $index => $row)
                    <tr>
                        <td class="center">{{ $index + 1 }}</td>
                        <td><div class="name">{{ $row['acolhido_nome'] }}</div><div class="muted">Primeira avaliacao: {{ $row['primeira_avaliacao_em']?->format('d/m/Y H:i') ?? '-' }}</div><div class="muted">Ultima avaliacao: {{ $row['ultima_avaliacao_em']?->format('d/m/Y H:i') ?? '-' }}</div></td>
                        <td><div>{{ $row['profissional_nome'] }}</div><div class="muted">{{ $row['total_avaliadores'] }} usuario(s) avaliador(es)</div></td>
                        <td class="center"><span class="chip">{{ $row['total_votos'] }}</span></td>
                        <td class="score center">{{ $formatScore((float) $row['media_geral_votos']) }}</td>
                        <td class="score center">{{ $formatScore((float) $row['media_de_todos']) }}</td>
                        <td class="center">{{ $row['formula_texto'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="formula-box">
        <div class="formula-title">Explicacao simples da media de todos</div>
        <div class="formula-line">1. Pegamos todas as notas dadas por cada usuario para o mesmo acolhido.</div>
        <div class="formula-line">2. Calculamos a media individual de cada usuario.</div>
        <div class="formula-line">3. Somamos essas medias individuais.</div>
        <div class="formula-line">4. Dividimos essa soma pela quantidade de usuarios que votaram.</div>
        <div class="formula-line"><strong>Em palavras:</strong> a media de todos e a soma das medias de cada avaliador dividida pela quantidade de avaliadores.</div>
    </div>
@endsection

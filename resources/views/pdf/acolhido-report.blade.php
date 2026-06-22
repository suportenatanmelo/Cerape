@extends('pdf.layout')

@section('title', 'Relatorio geral do acolhido')

@section('content')
    <style>
        .hero { background: linear-gradient(135deg, #92400e, #b45309); border-radius: 14px; color: #fff; margin-bottom: 14px; padding: 16px; }
        .hero-table { width: 100%; }
        .avatar-wrap { vertical-align: top; width: 92px; }
        .avatar { background: #fff; border: 3px solid rgba(255, 255, 255, 0.35); border-radius: 50%; height: 78px; object-fit: cover; width: 78px; }
        .avatar-empty { background: rgba(255, 255, 255, 0.18); border: 2px dashed rgba(255, 255, 255, 0.45); border-radius: 50%; color: #fff; height: 78px; padding-top: 28px; text-align: center; width: 78px; }
        .eyebrow { font-size: 8px; font-weight: bold; letter-spacing: 0.14em; text-transform: uppercase; }
        h1 { font-size: 20px; margin: 4px 0 6px; }
        .hero-text { color: rgba(255, 255, 255, 0.9); font-size: 9px; margin: 2px 0; }
        .status { border-radius: 999px; display: inline-block; font-size: 9px; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
        .summary { margin: 0 0 14px; width: 100%; }
        .summary td { padding: 0 5px; vertical-align: top; width: 33.33%; }
        .summary-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; min-height: 70px; padding: 10px; }
        .summary-label { color: #6b7280; display: block; font-size: 8px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; }
        .summary-value { color: #111827; display: block; font-size: 13px; font-weight: bold; margin-top: 5px; }
        .summary-note { color: #475569; display: block; font-size: 8px; margin-top: 4px; }
        .section { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; margin-bottom: 12px; overflow: hidden; page-break-inside: avoid; }
        .section-title { background: #fff7ed; border-bottom: 1px solid #fed7aa; color: #9a3412; font-size: 13px; font-weight: bold; margin: 0; padding: 10px 12px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 9px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        .inner-table th, .inner-table td { border-left: none; border-right: none; }
        .inner-table tr:first-child th, .inner-table tr:first-child td { border-top: none; }
        .inner-table tr:last-child th, .inner-table tr:last-child td { border-bottom: none; }
    </style>

    <div class="hero">
        <table class="hero-table">
            <tr>
                <td>
                    <div class="eyebrow">Perfil do acolhido</div>
                    <h1>Relatorio personalizado do acolhido em PDF</h1>
                    <div class="hero-text"><strong>{{ $acolhido->nome_completo_paciente }}</strong></div>
                    <div class="hero-text">Responsavel pelo cadastro: {{ $acolhido->user?->name ?? '-' }}</div>
                    <div class="hero-text">Emitido em: {{ now()->format('d/m/Y H:i') }}</div>
                    <span class="status {{ $acolhido->ativo ? 'status-active' : 'status-inactive' }}">
                        {{ $acolhido->ativo ? 'Ativo' : 'Desativado' }}
                    </span>
                </td>
            </tr>
        </table>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-card">
                    <span class="summary-label">Secoes selecionadas</span>
                    <span class="summary-value">{{ $selectedSectionsCount }} de {{ $availableSectionsCount }}</span>
                    <span class="summary-note">Quantidade de blocos incluidos neste PDF.</span>
                </div>
            </td>
            <td>
                <div class="summary-card">
                    <span class="summary-label">Tipo de relatorio</span>
                    <span class="summary-value">{{ $selectedSectionsCount === $availableSectionsCount ? 'Geral' : 'Personalizado' }}</span>
                    <span class="summary-note">{{ $selectedSectionsCount === $availableSectionsCount ? 'Todas as secoes foram marcadas.' : 'Somente as secoes escolhidas foram exportadas.' }}</span>
                </div>
            </td>
            <td>
                <div class="summary-card">
                    <span class="summary-label">Secoes incluidas</span>
                    <span class="summary-value">{{ $selectedSectionsLabel }}</span>
                    <span class="summary-note">Resumo das partes exibidas no documento.</span>
                </div>
            </td>
        </tr>
    </table>

    @foreach ($sections as $title => $fields)
        <div class="section">
            <h2 class="section-title">{{ $title }}</h2>
            <table class="inner-table">
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

    @if ($acolhido->avaliacoesPessoais->isNotEmpty())
        <div class="section">
            <h2 class="section-title">Avaliacoes pessoais registradas</h2>
            <table class="inner-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Usuario</th>
                        <th>Tempo de casa</th>
                        <th>Media</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($acolhido->avaliacoesPessoais->sortByDesc('created_at') as $avaliacao)
                        <tr>
                            <td>{{ $avaliacao->created_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ $avaliacao->user?->name ?? '-' }}</td>
                            <td>{{ $avaliacao->dias_na_casa ?? '-' }}</td>
                            <td>{{ number_format((float) $avaliacao->Total, 2, ',', '.') }} / 3</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

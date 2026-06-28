@extends('pdf.layout')

@section('title', 'Relatorio geral do acolhido')

@section('content')
    <style>
        .report-hero { background: #ffffff; border: 1px solid #dbe4ea; border-radius: 14px; margin-bottom: 12px; overflow: hidden; }
        .report-hero-top { background: linear-gradient(135deg, #0f766e, #134e4a); color: #fff; padding: 16px 18px; }
        .summary { margin: 0 0 12px; width: 100%; }
        .summary td { padding: 0 4px; vertical-align: top; width: 33.33%; }
        .summary-card { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; min-height: 74px; padding: 10px 12px; }
        .summary-label { color: #64748b; display: block; font-size: 8px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; }
        .summary-value { color: #0f172a; display: block; font-size: 13px; font-weight: bold; margin-top: 5px; }
        .summary-note { color: #475569; display: block; font-size: 8px; margin-top: 4px; line-height: 1.25; }
        .section { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; margin-bottom: 12px; overflow: hidden; page-break-inside: avoid; }
        .section-title { background: #f8fafc; border-bottom: 1px solid #dbe4ea; color: #0f172a; font-size: 12px; font-weight: bold; margin: 0; padding: 10px 12px; text-transform: uppercase; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 9px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        .inner-table th, .inner-table td { border-left: none; border-right: none; }
        .inner-table tr:first-child th, .inner-table tr:first-child td { border-top: none; }
        .inner-table tr:last-child th, .inner-table tr:last-child td { border-bottom: none; }
        .spaced { margin-top: 2px; }
    </style>

    <div class="report-hero">
        <div class="report-hero-top">
            <div class="report-eyebrow">Perfil do acolhido</div>
            <div class="report-title report-title--compact">Relatorio personalizado do acolhido em PDF</div>
            <div class="report-subtitle"><strong>{{ $acolhido->nome_completo_paciente }}</strong></div>
            <div class="report-subtitle">Responsavel pelo cadastro: {{ $acolhido->user?->name ?? '-' }}</div>
            <div class="report-subtitle">Emitido em: {{ now()->format('d/m/Y H:i') }}</div>
            <span class="report-badge {{ $acolhido->ativo ? 'report-badge--teal' : 'report-badge--amber' }}">
                {{ $acolhido->ativo ? 'Ativo' : 'Desativado' }}
            </span>
        </div>
    </div>

    <table class="summary">
        <tr>
            <td>
                <div class="summary-card">
                    <span class="summary-label">Seções selecionadas</span>
                    <span class="summary-value">{{ $selectedSectionsCount }} de {{ $availableSectionsCount }}</span>
                    <span class="summary-note">Quantidade de blocos incluidos neste PDF.</span>
                </div>
            </td>
            <td>
                <div class="summary-card">
                    <span class="summary-label">Tipo de relatório</span>
                    <span class="summary-value">{{ $selectedSectionsCount === $availableSectionsCount ? 'Geral' : 'Personalizado' }}</span>
                    <span class="summary-note">{{ $selectedSectionsCount === $availableSectionsCount ? 'Todas as seções foram marcadas.' : 'Somente as seções escolhidas foram exportadas.' }}</span>
                </div>
            </td>
            <td>
                <div class="summary-card">
                    <span class="summary-label">Seções incluídas</span>
                    <span class="summary-value">{{ $selectedSectionsLabel }}</span>
                    <span class="summary-note">Resumo das partes exibidas no documento.</span>
                </div>
            </td>
        </tr>
    </table>

    @foreach ($sections as $title => $fields)
        <div class="section">
            <h2 class="report-section-title--soft">{{ $title }}</h2>
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
            <h2 class="report-section-title--soft">Avaliações pessoais registradas</h2>
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

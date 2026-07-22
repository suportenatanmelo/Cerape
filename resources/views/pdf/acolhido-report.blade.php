@extends('pdf.layouts.cerape')

@section('title', 'Relatorio geral do acolhido')

@section('content')
    <style>
        .report-hero { background: #ffffff; border: 1px solid #dbe4ea; border-radius: 10px; margin-bottom: 8px; overflow: hidden; }
        .report-hero-top { background: linear-gradient(135deg, #0f766e, #134e4a); color: #fff; padding: 12px 14px; }
        .summary { margin: 0 0 10px; width: 100%; }
        .summary td { padding: 0 3px; vertical-align: top; width: 33.33%; }
        .summary-card { background: #fff; border: 1px solid #dbe4ea; border-radius: 10px; min-height: 60px; padding: 8px 10px; }
        .summary-label { color: #64748b; display: block; font-size: 7.5px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; }
        .summary-value { color: #0f172a; display: block; font-size: 12px; font-weight: bold; margin-top: 4px; }
        .summary-note { color: #475569; display: block; font-size: 7.5px; margin-top: 3px; line-height: 1.2; }
        .section { background: #fff; border: 1px solid #dbe4ea; border-radius: 10px; margin-bottom: 10px; overflow: hidden; page-break-inside: avoid; }
        .section-title { background: #f8fafc; border-bottom: 1px solid #dbe4ea; color: #0f172a; font-size: 10px; font-weight: bold; margin: 0; padding: 8px 10px; text-transform: uppercase; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 8.5px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #e5e7eb; padding: 5px 6px; vertical-align: top; }
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


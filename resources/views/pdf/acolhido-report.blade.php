<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio geral do acolhido</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.45; margin: 0; }
        .page { padding: 28px; }
        .brand-bar { border-bottom: 1px solid #e5e7eb; margin-bottom: 16px; padding-bottom: 12px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 62px; max-width: 220px; }
        .header { border-bottom: 2px solid #d97706; display: table; padding-bottom: 18px; width: 100%; }
        .avatar-wrap { display: table-cell; vertical-align: top; width: 96px; }
        .avatar { border: 2px solid #e5e7eb; border-radius: 50%; height: 82px; object-fit: cover; width: 82px; }
        .avatar-empty { background: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 50%; color: #6b7280; height: 82px; padding-top: 29px; text-align: center; width: 82px; }
        .title-wrap { display: table-cell; vertical-align: top; }
        h1 { font-size: 22px; margin: 0 0 6px; }
        h2 { color: #92400e; font-size: 15px; margin: 22px 0 10px; }
        .muted { color: #6b7280; }
        .status { border-radius: 999px; display: inline-block; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 10px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        .section { page-break-inside: avoid; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 10px; margin-top: 26px; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="page">
        @if ($logoCerape)
            <div class="brand-bar">
                <img src="{{ $logoCerape }}" class="brand-logo" alt="Logo Cerape">
            </div>
        @endif

        <div class="header">
            <div class="avatar-wrap">
                @if ($fotoAcolhido)
                    <img src="{{ $fotoAcolhido }}" class="avatar" alt="">
                @else
                    <div class="avatar-empty">Sem foto</div>
                @endif
            </div>
            <div class="title-wrap">
                <h1>Relatorio geral do acolhido</h1>
                <div><strong>{{ $acolhido->nome_completo_paciente }}</strong></div>
                <div class="muted">Responsavel: {{ $acolhido->user?->name ?? '-' }}</div>
                <div class="muted">Emitido em: {{ now()->format('d/m/Y H:i') }}</div>
                <span class="status {{ $acolhido->ativo ? 'status-active' : 'status-inactive' }}">
                    {{ $acolhido->ativo ? 'Ativo' : 'Desativado' }}
                </span>
            </div>
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

        @if ($acolhido->avaliacoesPessoais->isNotEmpty())
            <div class="section">
                <h2>Avaliacoes pessoais registradas</h2>
                <table>
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

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape.
        </div>
    </div>
</body>
</html>

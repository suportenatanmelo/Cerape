<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio de substancia psicoativa</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.45; margin: 0; }
        .page { padding: 28px; }
        .brand-bar { border-bottom: 1px solid #e5e7eb; margin-bottom: 16px; padding-bottom: 12px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 62px; max-width: 220px; }
        .header { border-bottom: 2px solid #d97706; padding-bottom: 18px; width: 100%; }
        h1 { font-size: 22px; margin: 0 0 6px; }
        h2 { color: #92400e; font-size: 15px; margin: 22px 0 10px; }
        .muted { color: #6b7280; }
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
            <h1>Relatorio de substancia psicoativa</h1>
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

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape.
        </div>
    </div>
</body>
</html>

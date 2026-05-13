<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatorio de prontuario de evolucao</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.5; margin: 0; }
        .page { padding: 26px; }
        .brand-bar { border-bottom: 1px solid #e5e7eb; margin-bottom: 16px; padding-bottom: 12px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 60px; max-width: 220px; }
        .hero { border-bottom: 2px solid #0f766e; display: table; padding-bottom: 18px; width: 100%; }
        .photo-wrap { display: table-cell; vertical-align: top; width: 126px; }
        .photo { border: 2px solid #d1d5db; border-radius: 14px; height: 156px; object-fit: cover; width: 116px; }
        .photo-empty { background: #f3f4f6; border: 2px solid #d1d5db; border-radius: 14px; color: #6b7280; height: 156px; line-height: 156px; text-align: center; width: 116px; }
        .hero-content { display: table-cell; padding-left: 16px; vertical-align: top; }
        h1 { font-size: 20px; margin: 0 0 6px; }
        h2 { color: #0f766e; font-size: 14px; margin: 22px 0 10px; }
        .muted { color: #6b7280; }
        .pill { background: #ccfbf1; border-radius: 999px; color: #115e59; display: inline-block; font-size: 10px; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        .grid { margin-top: 16px; width: 100%; }
        .grid-item { border: 1px solid #e5e7eb; display: inline-block; margin: 0 8px 8px 0; min-height: 54px; padding: 8px 10px; vertical-align: top; width: 31.5%; }
        .grid-label { color: #6b7280; font-size: 9px; font-weight: bold; margin-bottom: 4px; text-transform: uppercase; }
        .grid-value { color: #111827; font-size: 11px; }
        .section { page-break-inside: avoid; }
        .clinical-box { border: 1px solid #d1d5db; border-radius: 14px; margin-top: 8px; padding: 18px; }
        .clinical-box p { margin: 0 0 10px; }
        .clinical-box img { border-radius: 12px; display: block; height: auto; margin: 10px 0; max-width: 100%; }
        .clinical-box ul, .clinical-box ol { margin: 8px 0 8px 22px; padding: 0; }
        .clinical-box table { border-collapse: collapse; margin-top: 10px; width: 100%; }
        .clinical-box th, .clinical-box td { border: 1px solid #e5e7eb; padding: 6px; vertical-align: top; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 9px; margin-top: 24px; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="page">
        @if ($logoCerape)
            <div class="brand-bar">
                <img src="{{ $logoCerape }}" class="brand-logo" alt="Logo Cerape">
            </div>
        @endif

        <div class="hero">
            <div class="photo-wrap">
                @if ($fotoAcolhido)
                    <img src="{{ $fotoAcolhido }}" class="photo" alt="">
                @else
                    <div class="photo-empty">Sem foto</div>
                @endif
            </div>
            <div class="hero-content">
                <h1>Relatorio de prontuario de evolucao</h1>
                <div><strong>{{ $acolhido?->nome_completo_paciente ?? 'Acolhido nao identificado' }}</strong></div>
                <div class="muted">Prontuario registrado em: {{ $record->data_prontuario?->format('d/m/Y H:i') ?? '-' }}</div>
                <div class="muted">Registrado por: {{ $record->user?->name ?? '-' }}</div>
                <div class="muted">Emitido em: {{ now()->format('d/m/Y H:i') }}</div>
                <div class="pill">Evolucao clinica estruturada</div>
            </div>
        </div>

        <div class="grid">
            @foreach ($personalData as $item)
                <div class="grid-item">
                    <div class="grid-label">{{ $item['label'] }}</div>
                    <div class="grid-value">{{ $item['value'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="section">
            <h2>Evolucao registrada</h2>
            <div class="clinical-box">{!! $conteudoHtml !!}</div>
        </div>

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape.
        </div>
    </div>
</body>
</html>

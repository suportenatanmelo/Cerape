<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $payload['title'] }}</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1.6; margin: 0; }
        .page { padding: 26px 30px; }
        .document-shell { background: #fffef9; border: 1px solid #e5e7eb; padding: 26px 28px; }
        .doc-title { color: #0f172a; font-size: 18px; font-weight: bold; letter-spacing: 0.06em; margin: 0 0 26px; text-align: center; text-transform: uppercase; }
        .doc-paragraph { margin: 0 0 14px; text-align: justify; }
        .doc-date { margin: 26px 0 22px; text-align: left; }
        .doc-signature { margin-top: 34px; text-align: center; }
        .signature-line { border-top: 1px solid #111827; display: inline-block; min-width: 260px; padding-top: 6px; }
        .two-lines { margin-top: 26px; }
        .two-lines .signature-line { display: block; margin-bottom: 20px; min-width: 280px; }
        .doc-list { margin: 8px 0 14px 0; padding-left: 0; }
        .doc-list div { margin-bottom: 6px; }
        .blank-block { border-bottom: 1px solid #111827; display: inline-block; min-height: 1.1em; min-width: 190px; vertical-align: baseline; }
        .blank-wide { min-width: 320px; }
        .blank-full { display: block; margin-top: 8px; min-width: 100%; }
        .doc-multi-line { border-bottom: 1px solid #111827; display: block; height: 20px; margin-top: 6px; width: 100%; }
        .doc-multi-line + .doc-multi-line { margin-top: 10px; }
        .document-footer { color: #6b7280; font-size: 10px; margin-top: 20px; text-align: center; }
    </style>
</head>
<body>
    <div class="page">
        <div class="document-shell">
            @include('declaracoes.partials.documento', ['payload' => $payload, 'mode' => 'pdf'])
        </div>

        <div class="document-footer">
            Documento gerado automaticamente pelo sistema Cerape.
        </div>
    </div>
</body>
</html>

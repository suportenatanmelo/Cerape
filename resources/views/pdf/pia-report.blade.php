<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>PLANO INDIVIDUAL DE ACOLHIMENTO</title>
    <style>
        * { box-sizing: border-box; }
        body { background: #ffffff; color: #0f172a; font-family: DejaVu Serif, serif; font-size: 12px; line-height: 1.5; margin: 0; }
        .page { padding: 28px 30px; }
        .hero { background: #f8fafc; border: 1px solid #dbe4ea; border-radius: 16px; color: #0f172a; margin-bottom: 14px; padding: 18px; }
        .hero-table { width: 100%; }
        .avatar-wrap { vertical-align: top; width: 92px; }
        .avatar { background: #fff; border: 3px solid #cbd5e1; border-radius: 18px; height: 78px; object-fit: cover; width: 78px; }
        .avatar-empty { background: #e2e8f0; border: 2px dashed #94a3b8; border-radius: 18px; color: #334155; height: 78px; padding-top: 28px; text-align: center; width: 78px; }
        .eyebrow { font-size: 8px; font-weight: bold; letter-spacing: 0.14em; text-transform: uppercase; }
        h1 { font-size: 19px; margin: 4px 0 6px; text-transform: uppercase; }
        .hero-text { color: #334155; font-size: 9px; margin: 2px 0; }
        .status { border-radius: 999px; display: inline-block; font-size: 9px; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #991b1b; }
        .summary { margin: 0 0 14px; width: 100%; }
        .summary td { padding: 0 5px; vertical-align: top; width: 33.33%; }
        .summary-card { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; min-height: 70px; padding: 10px; }
        .summary-label { color: #6b7280; display: block; font-size: 8px; font-weight: bold; letter-spacing: 0.08em; text-transform: uppercase; }
        .summary-value { color: #111827; display: block; font-size: 13px; font-weight: bold; margin-top: 5px; }
        .summary-note { color: #475569; display: block; font-size: 8px; margin-top: 4px; }
        .section { background: #fff; border: 1px solid #dbe4ea; border-radius: 12px; margin-bottom: 12px; overflow: hidden; page-break-inside: avoid; }
        .section-title { background: #eff6ff; border-bottom: 1px solid #bfdbfe; color: #1d4ed8; font-size: 13px; font-weight: bold; margin: 0; padding: 10px 12px; text-transform: uppercase; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f8fafc; color: #334155; font-size: 9px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #dbe4ea; padding: 7px; vertical-align: top; }
        .inner-table th, .inner-table td { border-left: none; border-right: none; }
        .inner-table tr:first-child th, .inner-table tr:first-child td { border-top: none; }
        .inner-table tr:last-child th, .inner-table tr:last-child td { border-bottom: none; }
        .footer { border-top: 1px solid #dbe4ea; color: #64748b; font-size: 9px; margin-top: 20px; padding-top: 8px; text-align: center; }
    </style>
</head>
<body>
    <div class="page">
        @include('pdf.partials.cerape-brand-header')

        <div class="hero">
            <table class="hero-table">
                <tr>
                    <td class="avatar-wrap">
                        @if ($photoData)
                            <img src="{{ $photoData }}" class="avatar" alt="">
                        @else
                            <div class="avatar-empty">{{ $photoLabel ?? 'PIA' }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="eyebrow" style="color: #0f766e;">Plano individual</div>
                        <h1>{{ $title }}</h1>
                        <div class="hero-text"><strong>{{ $subtitle }}</strong></div>
                        @foreach ($metaLines as $line)
                            <div class="hero-text">{{ $line }}</div>
                        @endforeach
                        <span class="status {{ $highlight === 'PLANO COMPLETO' ? 'status-active' : 'status-inactive' }}">
                            {{ $highlight }}
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <table class="summary">
            <tr>
                <td>
                    <div class="summary-card">
                        <span class="summary-label">Módulos selecionados</span>
                        <span class="summary-value">{{ count($sections) }}</span>
                        <span class="summary-note">Quantidade de módulos incluídos no plano.</span>
                    </div>
                </td>
                <td>
                    <div class="summary-card">
                        <span class="summary-label">Documento</span>
                        <span class="summary-value">Plano Individual de Acolhimento</span>
                        <span class="summary-note">Documento preparado para arquivo institucional.</span>
                    </div>
                </td>
                <td>
                    <div class="summary-card">
                        <span class="summary-label">Acolhido</span>
                        <span class="summary-value">{{ $subtitle }}</span>
                        <span class="summary-note">Identificação principal do plano.</span>
                    </div>
                </td>
            </tr>
        </table>

        @foreach ($sections as $moduleTitle => $fields)
            <div class="section">
                <h2 class="section-title">{{ $moduleTitle }}</h2>
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

        <div class="footer">
            Documento gerado automaticamente pelo sistema CERAPE.
        </div>
    </div>
</body>
</html>

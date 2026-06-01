<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { box-sizing: border-box; }
        body { background: #f5f7fb; color: #0f172a; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.55; margin: 0; }
        .page { padding: 24px; }
        .brand-bar { border-bottom: 1px solid #dbe4ea; margin-bottom: 16px; padding-bottom: 12px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 62px; max-width: 220px; }
        .hero { background: linear-gradient(135deg, #0f766e, #134e4a); border-radius: 16px; color: #fff; display: table; margin-bottom: 16px; padding: 16px 18px; width: 100%; }
        .hero-photo, .hero-content { display: table-cell; vertical-align: top; }
        .hero-photo { width: 140px; }
        .photo { border: 3px solid rgba(255, 255, 255, 0.35); border-radius: 16px; height: 118px; object-fit: cover; width: 118px; }
        .photo-empty { background: rgba(255, 255, 255, 0.12); border: 2px dashed rgba(255, 255, 255, 0.45); border-radius: 16px; color: #fff; height: 118px; line-height: 118px; text-align: center; width: 118px; }
        h1 { font-size: 22px; margin: 0 0 6px; }
        h2 { color: #0f766e; font-size: 14px; margin: 22px 0 10px; }
        .muted { color: rgba(255, 255, 255, 0.9); margin-bottom: 3px; }
        .highlight { background: #dcfce7; border-radius: 999px; color: #166534; display: inline-block; font-size: 10px; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f8fafc; color: #334155; font-size: 10px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #dbe4ea; padding: 8px; vertical-align: top; }
        .section { background: #fff; border: 1px solid #dbe4ea; border-radius: 14px; margin-bottom: 12px; overflow: hidden; page-break-inside: avoid; }
        .section h2 { background: #f8fafc; border-bottom: 1px solid #dbe4ea; margin: 0; padding: 10px 12px; }
        .section table { border-left: 0; border-right: 0; }
        .footer { border-top: 1px solid #dbe4ea; color: #64748b; font-size: 9px; margin-top: 22px; padding-top: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="page">
        @include('pdf.partials.cerape-brand-header')

        <div class="hero">
            @if (! empty($photoData))
                <div class="hero-photo">
                    <img src="{{ $photoData }}" class="photo" alt="">
                </div>
            @elseif (! empty($photoLabel))
                <div class="hero-photo">
                    <div class="photo-empty">{{ $photoLabel }}</div>
                </div>
            @endif

            <div class="hero-content">
                <h1>{{ $title }}</h1>
                @if (! empty($subtitle))
                    <div class="muted"><strong>{{ $subtitle }}</strong></div>
                @endif
                @foreach ($metaLines as $line)
                    <div class="muted">{{ $line }}</div>
                @endforeach
                @if (! empty($highlight))
                    <div class="highlight">{{ $highlight }}</div>
                @endif
            </div>
        </div>

        @foreach ($sections as $sectionTitle => $fields)
            <div class="section">
                <h2>{{ $sectionTitle }}</h2>
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
            Documento gerado automaticamente pelo sistema CERAPE.
        </div>
    </div>
</body>
</html>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.45; margin: 0; }
        .page { padding: 26px; }
        .brand-bar { border-bottom: 1px solid #e5e7eb; margin-bottom: 16px; padding-bottom: 12px; width: 100%; }
        .brand-logo { display: block; height: auto; max-height: 62px; max-width: 220px; }
        .hero { border-bottom: 2px solid #d97706; display: table; padding-bottom: 18px; width: 100%; }
        .hero-photo, .hero-content { display: table-cell; vertical-align: top; }
        .hero-photo { width: 128px; }
        .photo { border: 2px solid #e5e7eb; border-radius: 14px; height: 116px; object-fit: cover; width: 116px; }
        .photo-empty { background: #f3f4f6; border: 2px solid #e5e7eb; border-radius: 14px; color: #6b7280; height: 116px; line-height: 116px; text-align: center; width: 116px; }
        h1 { font-size: 21px; margin: 0 0 6px; }
        h2 { color: #92400e; font-size: 14px; margin: 22px 0 10px; }
        .muted { color: #6b7280; margin-bottom: 3px; }
        .highlight { background: #fef3c7; border-radius: 999px; color: #92400e; display: inline-block; font-size: 10px; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        table { border-collapse: collapse; width: 100%; }
        th { background: #f9fafb; color: #4b5563; font-size: 10px; text-align: left; text-transform: uppercase; width: 34%; }
        th, td { border: 1px solid #e5e7eb; padding: 7px; vertical-align: top; }
        .section { page-break-inside: avoid; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 9px; margin-top: 26px; padding-top: 10px; }
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
                <div class="muted"><strong>{{ $subtitle }}</strong></div>
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
            Relatorio gerado automaticamente pelo sistema Cerape.
        </div>
    </div>
</body>
</html>

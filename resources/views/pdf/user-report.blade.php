<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatório do usuário</title>
    <style>
        @page {
            margin: 28px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            color: #1f2937;
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.45;
            margin: 0;
        }

        .header {
            border-bottom: 2px solid #92400e;
            margin-bottom: 18px;
            min-height: 78px;
            padding-bottom: 14px;
        }

        .avatar {
            border: 2px solid #f3f4f6;
            border-radius: 50%;
            float: right;
            height: 64px;
            object-fit: cover;
            width: 64px;
        }

        .eyebrow {
            color: #92400e;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        h1 {
            color: #111827;
            font-size: 22px;
            line-height: 1.2;
            margin: 0 0 6px;
        }

        .meta {
            color: #6b7280;
            font-size: 11px;
        }

        .section {
            margin-bottom: 16px;
            page-break-inside: avoid;
        }

        h2 {
            background: #f9fafb;
            border-left: 4px solid #92400e;
            color: #111827;
            font-size: 13px;
            margin: 0;
            padding: 8px 10px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border-bottom: 1px solid #e5e7eb;
            padding: 7px 10px;
            text-align: left;
            vertical-align: top;
        }

        th {
            color: #4b5563;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            width: 34%;
        }

        td {
            color: #111827;
            word-break: break-word;
        }

        .footer {
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 10px;
            margin-top: 18px;
            padding-top: 10px;
            text-align: right;
        }
    </style>
</head>
<body>
    <header class="header">
        @if ($avatarPath)
            <img class="avatar" src="{{ $avatarPath }}" alt="Avatar de {{ $user->name }}">
        @endif

        <div class="eyebrow">Relatório do usuário</div>
        <h1>{{ $user->name ?: 'Usuário sem nome informado' }}</h1>
        <div class="meta">
            Gerado em {{ now()->format('d/m/Y H:i') }}
        </div>
    </header>

    @foreach ($sections as $title => $items)
        <section class="section">
            <h2>{{ $title }}</h2>

            <table>
                <tbody>
                    @foreach ($items as $label => $value)
                        <tr>
                            <th>{{ $label }}</th>
                            <td>{{ $value }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @endforeach

    <footer class="footer">
        Documento gerado automaticamente pelo sistema.
    </footer>
</body>
</html>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $record->titulo }}</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.55; margin: 0; }
        .page { padding: 28px; }
        .header { border-bottom: 2px solid #0f766e; margin-bottom: 20px; padding-bottom: 14px; }
        .title { color: #0f172a; font-size: 22px; font-weight: bold; margin: 0 0 6px; }
        .subtitle { color: #475569; font-size: 12px; margin: 0 0 3px; }
        .meta { margin: 16px 0 20px; width: 100%; }
        .meta td { border: 1px solid #e5e7eb; padding: 8px 10px; vertical-align: top; }
        .meta .label { background: #f8fafc; color: #475569; font-weight: bold; width: 26%; }
        .section-title { color: #0f766e; font-size: 14px; font-weight: bold; margin: 20px 0 10px; }
        .box { border: 1px solid #dbe4ea; border-radius: 8px; padding: 14px; }
        .content p { margin: 0 0 10px; }
        .content ul, .content ol { margin: 0 0 12px 18px; padding: 0; }
        .footer { border-top: 1px solid #e5e7eb; color: #64748b; font-size: 9px; margin-top: 28px; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="page">
        @include('pdf.partials.cerape-brand-header')

        <div class="header">
            <h1 class="title">Ata de reunião</h1>
            <div class="subtitle"><strong>{{ $record->titulo }}</strong></div>
            <div class="subtitle">Documento gerado em {{ now()->format('d/m/Y H:i') }}</div>
        </div>

        <table class="meta">
            <tr>
                <td class="label">Título</td>
                <td>{{ $record->titulo }}</td>
            </tr>
            <tr>
                <td class="label">Responsável pelo registro</td>
                <td>{{ $responsavel }}</td>
            </tr>
            <tr>
                <td class="label">Data e hora da reunião</td>
                <td>{{ $record->data_reuniao?->format('d/m/Y H:i') ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Descrição</td>
                <td>{{ filled($descricao) ? $descricao : '-' }}</td>
            </tr>
        </table>

        <div class="section-title">Conteúdo da ata</div>
        <div class="box content">
            {!! $conteudoHtml !!}
        </div>

        <div class="footer">
            Documento emitido pelo sistema Cerape para registro administrativo e acompanhamento institucional.
        </div>
    </div>
</body>
</html>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Programacao de atividades</title>
    <style>
        * { box-sizing: border-box; }
        body { color: #111827; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.45; margin: 0; }
        .page { padding: 26px; }
        .hero { border-bottom: 2px solid #0f766e; padding-bottom: 18px; width: 100%; }
        .muted { color: #6b7280; margin-bottom: 4px; }
        .pill { background: #ccfbf1; border-radius: 999px; color: #115e59; display: inline-block; font-size: 10px; font-weight: bold; margin-top: 8px; padding: 4px 10px; }
        h1 { font-size: 21px; margin: 0 0 6px; }
        h2 { color: #0f766e; font-size: 14px; margin: 22px 0 10px; }
        .chips { margin-top: 8px; }
        .chip { background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 999px; display: inline-block; margin: 0 6px 6px 0; padding: 5px 10px; }
        .grid { width: 100%; }
        .grid-item { display: inline-block; vertical-align: top; width: 48%; }
        .card { border: 1px solid #d1d5db; border-radius: 14px; min-height: 180px; padding: 16px; }
        .card ul { margin: 0; padding-left: 18px; }
        .card li { margin-bottom: 7px; }
        .observacoes { border: 1px solid #d1d5db; border-radius: 14px; margin-top: 8px; padding: 16px; }
        .footer { border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 9px; margin-top: 24px; padding-top: 8px; }
    </style>
</head>
<body>
    <div class="page">
        @include('pdf.partials.cerape-brand-header')

        <div class="hero">
            <h1>{{ $record->titulo }}</h1>
            <div class="muted"><strong>Data da programacao:</strong> {{ $record->data_programacao?->format('d/m/Y') ?? '-' }}</div>
            <div class="muted"><strong>Responsavel:</strong> {{ $record->user?->name ?? '-' }}</div>
            <div class="muted"><strong>Emitido em:</strong> {{ now()->format('d/m/Y H:i') }}</div>
            <div class="pill">Planejamento diario organizado por turno</div>
        </div>

        <h2>Acolhidos selecionados</h2>
        <div class="chips">
            @forelse ($acolhidos as $acolhido)
                <span class="chip">{{ $acolhido }}</span>
            @empty
                <span class="chip">Nenhum acolhido selecionado</span>
            @endforelse
        </div>

        <h2>Atividades programadas</h2>
        <div class="grid">
            <div class="grid-item">
                <div class="card">
                    <strong>Turno matutino</strong>
                    <ul>
                        @forelse ($atividadesMatutinas as $atividade)
                            <li>{{ $atividade }}</li>
                        @empty
                            <li>Nenhuma atividade matutina selecionada.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="grid-item" style="margin-left: 3.5%;">
                <div class="card">
                    <strong>Turno vespertino</strong>
                    <ul>
                        @forelse ($atividadesVespertinas as $atividade)
                            <li>{{ $atividade }}</li>
                        @empty
                            <li>Nenhuma atividade vespertina selecionada.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <h2>Observacoes complementares</h2>
        <div class="observacoes">
            {{ filled($record->observacoes) ? $record->observacoes : 'Sem observacoes adicionais.' }}
        </div>

        <div class="footer">
            Relatorio gerado automaticamente pelo sistema Cerape.
        </div>
    </div>
</body>
</html>

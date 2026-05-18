<x-filament-panels::page>
    <style>
        .declaration-layout {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: minmax(18rem, 24rem) minmax(0, 1fr);
        }

        .declaration-panel,
        .declaration-preview {
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.96));
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 1.5rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .declaration-panel {
            padding: 1.25rem;
        }

        .declaration-preview {
            overflow: hidden;
        }

        .declaration-preview-header {
            align-items: center;
            background: linear-gradient(135deg, #0f766e, #115e59);
            color: #f8fafc;
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.25rem;
        }

        .declaration-preview-meta {
            color: rgba(248, 250, 252, 0.85);
            font-size: 0.92rem;
            margin-top: 0.25rem;
        }

        .paper-sheet {
            background: #fffef9;
            margin: 1.25rem;
            min-height: 980px;
            padding: 2.5rem 2.8rem;
            position: relative;
        }

        .paper-sheet::before {
            border: 1px solid rgba(217, 119, 6, 0.18);
            content: "";
            inset: 18px;
            pointer-events: none;
            position: absolute;
        }

        .page-note {
            color: #475569;
            font-size: 0.92rem;
            margin-top: 0.35rem;
        }

        .empty-state {
            color: #475569;
            padding: 4rem 2rem;
            text-align: center;
        }

        @media (max-width: 1100px) {
            .declaration-layout {
                grid-template-columns: 1fr;
            }

            .paper-sheet {
                margin: 0.9rem;
                padding: 1.6rem;
            }
        }
    </style>

    <div class="declaration-layout">
        <div class="declaration-panel">
            {{ $this->form }}
        </div>

        <div class="declaration-preview">
            <div class="declaration-preview-header">
                <div>
                    <div style="font-size: 1.05rem; font-weight: 700;">Visualizacao da declaracao</div>
                    <div class="declaration-preview-meta">Documento preparado para conferencia e assinatura manual.</div>
                </div>
                <div style="font-size: 0.88rem; font-weight: 600;">CERAPE / CRC</div>
            </div>

            @php($payload = $this->getPreviewPayload())

            @if ($payload)
                <div class="paper-sheet">
                    @include('declaracoes.partials.documento', ['payload' => $payload, 'mode' => 'preview'])
                </div>
            @else
                <div class="empty-state">
                    <div style="font-size: 1.08rem; font-weight: 700;">Selecione uma declaracao para continuar</div>
                    <div class="page-note">Quando a declaracao exigir acolhido, escolha um nome para liberar a visualizacao e o PDF.</div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>

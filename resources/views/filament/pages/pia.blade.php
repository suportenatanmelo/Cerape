<x-filament-panels::page>
    <style>
        .pia-layout {
            display: grid;
            gap: 1.5rem;
            grid-template-columns: minmax(18rem, 24rem) minmax(0, 1fr);
        }

        .pia-panel,
        .pia-preview {
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.96));
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 1.5rem;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        }

        .pia-panel {
            padding: 1.25rem;
        }

        .pia-preview {
            overflow: hidden;
        }

        .pia-preview-header {
            align-items: center;
            background: linear-gradient(135deg, #0f766e, #115e59);
            color: #f8fafc;
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.25rem;
        }

        .pia-preview-meta {
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

        .empty-state {
            color: #475569;
            padding: 4rem 2rem;
            text-align: center;
        }

        .page-note {
            color: #475569;
            font-size: 0.92rem;
            margin-top: 0.35rem;
        }

        .dark .pia-panel,
        .dark .pia-preview {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.96), rgba(15, 23, 42, 0.92));
            border-color: rgba(71, 85, 105, 0.55);
            box-shadow: 0 18px 40px rgba(2, 6, 23, 0.35);
        }

        .dark .pia-panel .fi-input-wrp,
        .dark .pia-panel .fi-dropdown-panel,
        .dark .pia-panel .fi-select-input-search-ctn {
            background: #ffffff !important;
            border-color: rgba(15, 23, 42, 0.14) !important;
        }

        .dark .pia-panel select,
        .dark .pia-panel select option,
        .dark .pia-panel select optgroup,
        .dark .pia-panel .fi-select-input,
        .dark .pia-panel .fi-select-input-btn,
        .dark .pia-panel .fi-select-input-option,
        .dark .pia-panel .fi-select-input-value-label,
        .dark .pia-panel [data-slot="input"],
        .dark .pia-panel .choices__inner,
        .dark .pia-panel input {
            color: #0f172a !important;
            -webkit-text-fill-color: #0f172a !important;
        }

        .dark .pia-panel select option,
        .dark .pia-panel select optgroup {
            background: #ffffff !important;
        }

        .dark .pia-panel .fi-select-input-placeholder,
        .dark .pia-panel input::placeholder {
            color: #64748b !important;
            -webkit-text-fill-color: #64748b !important;
        }

        @media (max-width: 1100px) {
            .pia-layout {
                grid-template-columns: 1fr;
            }

            .paper-sheet {
                margin: 0.9rem;
                padding: 1.6rem;
            }
        }
    </style>

    <div class="pia-layout">
        <div class="pia-panel">
            {{ $this->form }}
        </div>

        <div class="pia-preview">
            <div class="pia-preview-header">
                <div>
                    <div style="font-size: 1.05rem; font-weight: 700;">Visualização do PIA</div>
                    <div class="pia-preview-meta">Plano Individual de Acolhimento preparado para conferência e arquivo.</div>
                </div>
                <div style="font-size: 0.88rem; font-weight: 600;">CERAPE / CRC</div>
            </div>

            @php($payload = $this->getPreviewPayload())

            @if ($payload)
                <div class="paper-sheet">
                    @include('pdf.pia-report', $payload)
                </div>
            @else
                <div class="empty-state">
                    <div style="font-size: 1.08rem; font-weight: 700;">Selecione um acolhido e os módulos do PIA</div>
                    <div class="page-note">Depois disso, o PDF fica disponível no botão de download acima.</div>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>

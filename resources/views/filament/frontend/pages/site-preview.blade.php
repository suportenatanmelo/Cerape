<x-filament-panels::page>
    <div class="site-preview-shell">
        <aside class="site-preview-rail">
            <div class="site-preview-copy">
                <span class="site-preview-kicker">Frontend</span>
                <h2>Prévia da página principal</h2>
                <p>
                    Aqui a gente vê a home do site em tempo real, limpa e sem os blocos de gestão.
                    O menu do painel continua disponível ao lado para navegar entre os cadastros.
                </p>
            </div>
        </aside>

        <section class="site-preview-frame">
            <iframe src="{{ $this->getHomeUrl() }}" title="Prévia da página principal da CERAPE" loading="lazy"></iframe>
        </section>
    </div>

    <style>
        .site-preview-shell {
            display: grid;
            grid-template-columns: minmax(260px, 340px) minmax(0, 1fr);
            gap: 24px;
            align-items: stretch;
            min-height: calc(100vh - 10rem);
        }

        .site-preview-rail {
            position: sticky;
            top: 1.5rem;
            align-self: start;
        }

        .site-preview-copy {
            padding: 28px;
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(255, 248, 237, 0.92), rgba(255, 255, 255, 0.88));
            border: 1px solid rgba(226, 219, 203, 0.9);
            box-shadow: 0 18px 36px rgba(30, 61, 54, 0.08);
        }

        .site-preview-kicker {
            display: inline-flex;
            padding: 8px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #6b8e78;
            background: rgba(107, 142, 120, 0.12);
            margin-bottom: 16px;
        }

        .site-preview-copy h2 {
            margin: 0 0 12px;
            font-size: 1.8rem;
        }

        .site-preview-copy p {
            margin: 0;
            color: #6b6459;
            line-height: 1.7;
        }

        .site-preview-frame {
            min-height: calc(100vh - 10rem);
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid rgba(226, 219, 203, 0.9);
            box-shadow: 0 24px 70px rgba(30, 61, 54, 0.12);
            background: #fff;
        }

        .site-preview-frame iframe {
            width: 100%;
            height: 100%;
            min-height: calc(100vh - 10rem);
            border: 0;
            display: block;
            background: #fff;
        }

        @media (max-width: 1024px) {
            .site-preview-shell {
                grid-template-columns: 1fr;
            }

            .site-preview-rail {
                position: static;
            }
        }
    </style>
</x-filament-panels::page>

@php
    $settings = \App\Models\FrontendSetting::query()->first();
    $siteEnabled = (bool) ($settings?->site_enabled ?? true);
@endphp

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
                <p class="site-preview-status">
                    Status atual: <strong>{{ $siteEnabled ? 'site público habilitado' : 'site público desabilitado' }}</strong>.
                    Quando estiver desabilitado, a página principal fica oculta e o site exibe apenas <code>view('welcome')</code>.
                </p>
            </div>
        </aside>

        <section class="site-preview-frame">
            <iframe src="{{ $this->getHomeUrl() }}" title="Prévia da página principal da CERAPE" loading="lazy"></iframe>
        </section>
    </div>

    <footer
        class="site-preview-footer"
        x-data="{
            open: false,
            pendingStatus: {{ $siteEnabled ? '1' : '0' }},
            currentStatus: {{ $siteEnabled ? '1' : '0' }},
            password: '',
            submitting: false,
            feedback: '',
            feedbackType: '',
            openModal(nextStatus) {
                this.pendingStatus = nextStatus;
                this.password = '';
                this.feedback = '';
                this.feedbackType = '';
                this.open = true;
                this.$nextTick(() => this.$refs.password?.focus());
            },
            closeModal() {
                this.open = false;
                this.pendingStatus = this.currentStatus;
                this.password = '';
                this.feedback = '';
                this.feedbackType = '';
            },
            async submitStatus(event) {
                this.submitting = true;
                this.feedback = '';
                this.feedbackType = '';

                try {
                    const response = await fetch('{{ route('frontend.site-status') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(event.target),
                    });

                    const payload = await response.json().catch(() => ({}));

                    if (! response.ok) {
                        this.feedback = payload?.errors?.password?.[0] || payload?.message || 'A senha foi errada.';
                        this.feedbackType = 'error';
                        return;
                    }

                    this.currentStatus = this.pendingStatus;
                    this.feedback = payload?.message || 'Senha aprovada. Status atualizado com sucesso.';
                    this.feedbackType = 'success';
                    this.$nextTick(() => {
                        setTimeout(() => this.closeModal(), 1400);
                    });
                } catch (error) {
                    this.feedback = 'Não foi possível validar a senha agora.';
                    this.feedbackType = 'error';
                } finally {
                    this.submitting = false;
                }
            },
        }"
    >
        <div class="site-preview-footer__inner">
            <span class="site-preview-footer__copyright">© 2026 Natan Rosa de Melo. Todos os direitos reservados.</span>

            <div class="site-preview-footer__switch">
                <span class="site-preview-footer__label">Site público</span>
                <label class="site-preview-footer__radio">
                    <input type="radio" name="site_status_display_preview" :checked="currentStatus === 1" @click="openModal(1)">
                    <span>abilitado</span>
                </label>
                <label class="site-preview-footer__radio">
                    <input type="radio" name="site_status_display_preview" :checked="currentStatus === 0" @click="openModal(0)">
                    <span>desabilitado</span>
                </label>
            </div>
        </div>

        <div x-cloak x-show="open" class="site-preview-footer__modal" @keydown.escape.window="closeModal()">
            <div class="site-preview-footer__backdrop" @click="closeModal()"></div>

            <div class="site-preview-footer__dialog" @click.stop>
                <h3>Confirmar alteração</h3>
                <p>Digite a senha do usuário administrador para liberar ou bloquear o site público.</p>

                <form method="POST" action="{{ route('frontend.site-status') }}" class="site-preview-footer__form" @submit.prevent="submitStatus($event)">
                    @csrf
                    <input type="hidden" name="site_enabled" :value="pendingStatus">

                    <label class="site-preview-footer__field">
                        <span>Senha</span>
                        <input x-ref="password" type="password" name="password" x-model="password" placeholder="Digite a senha" required>
                    </label>

                    <p x-show="feedback" x-text="feedback" :class="feedbackType === 'success' ? 'site-preview-footer__success' : 'site-preview-footer__error'"></p>

                    <div class="site-preview-footer__actions">
                        <button type="button" class="site-preview-footer__button site-preview-footer__button--ghost" @click="closeModal()">
                            Cancelar
                        </button>
                        <button type="submit" class="site-preview-footer__button" :disabled="submitting">
                            Confirmar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </footer>

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

        .site-preview-status {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px dashed rgba(107, 142, 120, 0.22);
            font-size: 0.92rem;
        }

        .site-preview-status strong {
            color: #1e3d36;
        }

        .site-preview-status code {
            padding: 0.15rem 0.4rem;
            border-radius: 8px;
            background: rgba(30, 61, 54, 0.08);
            color: #1e3d36;
            font-size: 0.86rem;
        }

        .site-preview-frame {
            min-height: calc(100vh - 10rem);
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid rgba(226, 219, 203, 0.9);
            box-shadow: 0 24px 70px rgba(30, 61, 54, 0.12);
            background: #fff;
        }

        .site-preview-footer {
            grid-column: 1 / -1;
            margin-top: 18px;
            padding: 18px 20px;
            border-radius: 22px;
            border: 1px solid rgba(226, 219, 203, 0.9);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.94), rgba(255, 248, 237, 0.94));
            box-shadow: 0 14px 30px rgba(30, 61, 54, 0.08);
            color: #6b6459;
        }

        .site-preview-footer__inner {
            align-items: center;
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            justify-content: space-between;
            width: 100%;
        }

        .site-preview-footer__copyright {
            font-size: 0.86rem;
            font-weight: 600;
        }

        .site-preview-footer__switch {
            align-items: center;
            display: inline-flex;
            gap: 0.65rem;
            padding: 0.35rem 0.7rem;
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.7);
        }

        .site-preview-footer__label {
            font-weight: 700;
            color: #0f172a;
            font-size: 0.78rem;
        }

        .site-preview-footer__radio {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            cursor: pointer;
            user-select: none;
            color: #334155;
            font-size: 0.78rem;
        }

        .site-preview-footer__radio input {
            accent-color: #f59e0b;
        }

        .site-preview-footer__modal {
            position: fixed;
            inset: 0;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .site-preview-footer__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.58);
            backdrop-filter: blur(5px);
        }

        .site-preview-footer__dialog {
            position: relative;
            z-index: 1;
            width: min(100%, 420px);
            border-radius: 20px;
            background: #fff;
            border: 1px solid rgba(226, 232, 240, 0.95);
            box-shadow: 0 30px 80px rgba(15, 23, 42, 0.28);
            padding: 1.25rem;
        }

        .site-preview-footer__dialog h3 {
            margin: 0 0 0.4rem;
            color: #0f172a;
            font-size: 1.1rem;
        }

        .site-preview-footer__dialog p {
            margin: 0 0 1rem;
            color: #475569;
            line-height: 1.5;
        }

        .site-preview-footer__form {
            display: grid;
            gap: 0.9rem;
        }

        .site-preview-footer__field {
            display: grid;
            gap: 0.35rem;
            color: #0f172a;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .site-preview-footer__field input {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 0.8rem 0.9rem;
            font-size: 0.95rem;
            color: #0f172a;
            background: #fff;
        }

        .site-preview-footer__actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .site-preview-footer__button {
            border: 0;
            border-radius: 999px;
            padding: 0.75rem 1.1rem;
            font-weight: 700;
            background: #f59e0b;
            color: #fff;
        }

        .site-preview-footer__button--ghost {
            background: #e2e8f0;
            color: #0f172a;
        }

        .site-preview-footer__success {
            margin: 0;
            color: #15803d;
            font-size: 0.88rem;
        }

        .site-preview-footer__error {
            margin: 0;
            color: #b91c1c;
            font-size: 0.88rem;
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

            .site-preview-footer__inner {
                justify-content: center;
            }
        }

        [x-cloak] { display: none !important; }
    </style>
</x-filament-panels::page>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        @php
            use App\Support\MapEmbedResolver;

            $themeDefaults = \App\Support\FrontendThemePresets::profiles()[\App\Support\FrontendThemePresets::defaultProfileKey()];
            $theme = is_object($themeProfile ?? null)
                ? $themeProfile->toArray()
                : (array) ($themeProfile ?? []);
            $footer = is_object($footerSettings ?? null)
                ? $footerSettings->toArray()
                : (array) ($footerSettings ?? []);

            $theme = array_merge($themeDefaults, array_filter($theme, fn ($value): bool => filled($value)));
            $footer = array_merge([
                'brand_name' => 'CERAPE',
                'tagline' => 'Um frontend institucional pensado para comunicar com clareza, manter o conteudo organizado e facilitar o trabalho da equipe no Filament.',
                'address' => 'Fazenda - R. 5 A - Parque Alvorada III, Luziânia - GO, 72859-899',
                'email' => null,
                'phone' => null,
                'whatsapp' => null,
                'map_embed_code' => null,
                'map_embed_url' => 'https://www.google.com/maps?q=' . urlencode('Fazenda - R. 5 A - Parque Alvorada III, Luziânia - GO, 72859-899') . '&output=embed',
                'quick_links' => [],
                'social_links' => [],
                'copyright_text' => 'CERAPE. Todos os direitos reservados.',
                'use_theme_colors' => true,
                'background_color' => null,
                'text_color' => null,
                'muted_color' => null,
                'border_color' => null,
            ], array_filter($footer, fn ($value): bool => filled($value) || $value === false));

            $footerBackground = $footer['use_theme_colors']
                ? $theme['background_color']
                : ($footer['background_color'] ?: $theme['background_color']);
            $footerText = $footer['use_theme_colors']
                ? $theme['text_color']
                : ($footer['text_color'] ?: $theme['text_color']);
            $footerMuted = $footer['use_theme_colors']
                ? $theme['muted_color']
                : ($footer['muted_color'] ?: $theme['muted_color']);
            $footerBorder = $footer['use_theme_colors']
                ? 'color-mix(in srgb, ' . $theme['primary_color'] . ' 14%, transparent)'
                : ($footer['border_color'] ?: 'rgba(255,255,255,0.14)');

            if (blank($footer['map_embed_url']) && filled($footer['address'])) {
                $footer['map_embed_url'] = 'https://www.google.com/maps?q=' . urlencode($footer['address']) . '&output=embed';
            }

            $footerMapSrc = MapEmbedResolver::src(
                $footer['map_embed_code'] ?: $footer['map_embed_url'],
                $footer['address'] ?? null
            );
        @endphp
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="description" content="@yield('meta_description', 'Site institucional do CERAPE com blog, contato e conteudo gerenciado pelo Filament.')" />
        <title>@yield('title', config('app.name'))</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="{{ \App\Support\FrontendThemePresets::googleFontsUrl() }}" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --site-bg: {{ $theme['background_color'] }};
                --site-bg-alt: color-mix(in srgb, {{ $theme['background_color'] }} 82%, black);
                --site-text: {{ $theme['text_color'] ?? $theme['surface_color'] }};
                --site-muted: {{ $theme['muted_color'] ?? $theme['surface_strong_color'] }};
                --site-primary: {{ $theme['primary_color'] }};
                --site-secondary: {{ $theme['secondary_color'] }};
                --site-accent: {{ $theme['accent_color'] }};
                --site-ink: {{ $theme['ink_color'] }};
                --site-surface: color-mix(in srgb, {{ $theme['surface_color'] }} 10%, transparent);
                --site-surface-strong: color-mix(in srgb, {{ $theme['surface_strong_color'] }} 18%, transparent);
                --site-border: color-mix(in srgb, {{ $theme['primary_color'] }} 18%, transparent);
                --font-sans: '{{ $theme['body_font'] }}', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
                --font-display: '{{ $theme['display_font'] }}', ui-sans-serif, system-ui, sans-serif;
            }

            .site-footer {
                color: var(--footer-text, #ffffff);
            }

            .site-footer .text-white {
                color: var(--footer-text, #ffffff) !important;
            }

            .site-footer .text-white\/90,
            .site-footer .text-white\/80 {
                color: color-mix(in srgb, var(--footer-text, #ffffff) 90%, transparent) !important;
            }

            .site-footer .text-slate-300 {
                color: var(--footer-muted, rgba(255, 255, 255, 0.74)) !important;
            }

            .site-footer .text-slate-400 {
                color: var(--footer-muted, rgba(255, 255, 255, 0.58)) !important;
            }

            .site-footer .border-white\/10 {
                border-color: var(--footer-border, rgba(255, 255, 255, 0.12)) !important;
            }

            .site-footer .bg-white\/5 {
                background-color: color-mix(in srgb, var(--footer-text, #ffffff) 6%, transparent) !important;
            }

            .hero-banner,
            .hero-banner * {
                color: #ffffff !important;
            }

            .hero-banner .hero-soft {
                color: rgba(255, 255, 255, 0.74) !important;
            }

            .hero-banner .hero-muted {
                color: rgba(255, 255, 255, 0.88) !important;
            }
        </style>
        @stack('head')
    </head>
    <body class="min-h-screen antialiased selection:bg-[var(--site-primary)] selection:text-[var(--site-ink)]" style="background-color: var(--site-bg); color: var(--site-text);">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -left-24 top-0 h-72 w-72 rounded-full blur-3xl" style="background: color-mix(in srgb, var(--site-primary) 12%, transparent);"></div>
            <div class="absolute right-0 top-24 h-96 w-96 rounded-full blur-3xl" style="background: color-mix(in srgb, var(--site-accent) 10%, transparent);"></div>
            <div class="absolute bottom-0 left-1/3 h-80 w-80 rounded-full blur-3xl" style="background: color-mix(in srgb, var(--site-secondary) 10%, transparent);"></div>
        </div>

        <header class="sticky top-0 z-50 px-4 pt-4 sm:px-6 lg:px-8">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 rounded-[1.4rem] border border-slate-200 bg-white/95 px-4 py-4 shadow-[0_12px_40px_rgba(15,23,42,0.08)] backdrop-blur-xl sm:px-5 lg:px-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="flex h-12 w-12 items-center justify-center rounded-2xl border border-slate-200 bg-white shadow-lg shadow-slate-200/50">
                        <img src="{{ asset('images/logo.png') }}" alt="CERAPE" class="h-9 w-9 rounded-xl object-cover" />
                    </span>
                    <div>
                        <div class="font-display text-lg font-bold tracking-tight text-slate-900">CERAPE</div>
                        <div class="text-xs font-medium uppercase tracking-[0.3em]" style="color: color-mix(in srgb, var(--site-primary) 84%, #0f172a);">Portal institucional</div>
                    </div>
                </a>

                <nav class="hidden items-center gap-2 lg:flex">
                    <a href="{{ route('home') }}" @class([
                        'rounded-full px-4 py-2 text-sm font-semibold transition',
                        'bg-[color-mix(in_srgb,var(--site-primary)_12%,white)] text-[var(--site-primary)]' => request()->routeIs('home'),
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs('home'),
                    ])>Início</a>
                    <a href="{{ route('about') }}" @class([
                        'rounded-full px-4 py-2 text-sm font-semibold transition',
                        'bg-[color-mix(in_srgb,var(--site-primary)_12%,white)] text-[var(--site-primary)]' => request()->routeIs('about'),
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs('about'),
                    ])>Sobre</a>
                    <a href="{{ route('blog') }}" @class([
                        'rounded-full px-4 py-2 text-sm font-semibold transition',
                        'bg-[color-mix(in_srgb,var(--site-primary)_12%,white)] text-[var(--site-primary)]' => request()->routeIs('blog') || request()->routeIs('blog.show'),
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! (request()->routeIs('blog') || request()->routeIs('blog.show')),
                    ])>Blog</a>
                    <a href="{{ route('contact') }}" @class([
                        'rounded-full px-4 py-2 text-sm font-semibold transition',
                        'bg-[color-mix(in_srgb,var(--site-primary)_12%,white)] text-[var(--site-primary)]' => request()->routeIs('contact'),
                        'text-slate-600 hover:bg-slate-100 hover:text-slate-900' => ! request()->routeIs('contact'),
                    ])>Contato</a>
                </nav>

                <div class="hidden items-center gap-3 lg:flex">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-full px-5 py-2.5 text-sm font-bold shadow-lg transition" style="background: linear-gradient(135deg, var(--site-primary), color-mix(in srgb, var(--site-primary) 74%, var(--site-secondary))); color: #ffffff; box-shadow: 0 18px 30px color-mix(in srgb, var(--site-primary) 18%, transparent);">
                        Falar com a equipe
                    </a>
                </div>

                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-full border px-4 py-2 text-sm font-semibold transition hover:bg-slate-50 lg:hidden"
                    style="border-color: color-mix(in srgb, var(--site-primary) 14%, transparent); background: white; color: var(--site-text);"
                    data-site-nav-toggle
                    aria-controls="site-nav"
                    aria-expanded="false"
                >
                    Menu
                </button>
            </div>

            <div id="site-nav" class="hidden border-t px-4 py-4 lg:hidden" style="border-color: color-mix(in srgb, var(--site-primary) 10%, transparent); background: white;" data-site-nav>
                <div class="mx-auto flex max-w-7xl flex-col gap-2 sm:flex-row sm:flex-wrap">
                    <a href="{{ route('home') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800">Início</a>
                    <a href="{{ route('about') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800">Sobre</a>
                    <a href="{{ route('blog') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800">Blog</a>
                    <a href="{{ route('contact') }}" class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-800">Contato</a>
                </div>
            </div>
        </header>

        <main class="relative">
            @yield('content')
        </main>

        <footer class="site-footer border-t" style="--footer-text: {{ $footerText }}; --footer-muted: {{ $footerMuted }}; --footer-border: {{ $footerBorder }}; background: linear-gradient(180deg, {{ $footerBackground }} 0%, color-mix(in srgb, {{ $footerBackground }} 82%, black) 100%); border-color: var(--footer-border);">
            <div class="mx-auto grid max-w-7xl gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1.2fr_0.9fr_0.9fr_1fr] lg:px-8">
                @php
                    $contactData = $contactPage ? $contactPage->toArray() : [];
                    $footerSocialLinks = collect($footer['social_links'] ?? ($contactData['social_links'] ?? []));
                    $footerQuickLinks = collect($footer['quick_links'] ?? []);
                @endphp
                <div class="space-y-5">
                    <div class="space-y-3">
                        <div class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.28em] text-white/90">
                            Rodape profissional
                        </div>
                        <div class="font-display text-2xl font-bold text-white">
                            {{ $footer['brand_name'] ?: 'CERAPE' }}
                        </div>
                    </div>
                    <p class="max-w-xl text-sm leading-7 text-slate-300">
                        {{ $footer['tagline'] }}
                    </p>
                    @if (filled($footer['address']))
                        <div class="rounded-[1.4rem] border border-white/10 bg-white/5 p-4 text-sm leading-7 text-slate-300">
                            {{ $footer['address'] }}
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="text-sm font-bold uppercase tracking-[0.24em]" style="color: color-mix(in srgb, var(--site-primary) 86%, white);">Contato</div>
                    <div class="space-y-3 rounded-[1.4rem] border border-white/10 bg-white/5 p-5 text-sm leading-7 text-slate-300">
                        <div><span class="font-semibold text-white">E-mail:</span> {{ $footer['email'] ?: data_get($contactData, 'email') ?: 'contato@cerape.local' }}</div>
                        <div><span class="font-semibold text-white">Telefone:</span> {{ $footer['phone'] ?: data_get($contactData, 'phone') ?: '(00) 00000-0000' }}</div>
                        <div><span class="font-semibold text-white">WhatsApp:</span> {{ $footer['whatsapp'] ?: data_get($contactData, 'whatsapp') ?: 'Nao configurado' }}</div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="text-sm font-bold uppercase tracking-[0.24em]" style="color: color-mix(in srgb, var(--site-primary) 86%, white);">Links rapidos</div>
                    <div class="grid gap-3">
                        @forelse ($footerQuickLinks as $link)
                            @if (filled(data_get($link, 'url')))
                                <a href="{{ data_get($link, 'url') }}" class="rounded-3xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                    {{ data_get($link, 'label') ?: data_get($link, 'url') }}
                                </a>
                            @endif
                        @empty
                            <a href="{{ route('blog') }}" class="rounded-3xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                Blog
                            </a>
                            <a href="{{ route('contact') }}" class="rounded-3xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white transition hover:bg-white/10">
                                Contato
                            </a>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="text-sm font-bold uppercase tracking-[0.24em]" style="color: color-mix(in srgb, var(--site-primary) 86%, white);">Mapa e redes</div>
                    @if (filled($footerMapSrc))
                        <div class="overflow-hidden rounded-[1.4rem] border border-white/10 bg-white/5">
                            <iframe
                                src="{{ $footerMapSrc }}"
                                title="Mapa de localizacao"
                                class="h-52 w-full"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                        <a
                            href="{{ MapEmbedResolver::searchUrl($footer['address'] ?: $footer['brand_name']) }}"
                            target="_blank"
                            rel="noreferrer"
                            class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10"
                        >
                            Abrir no Google Maps
                        </a>
                    @else
                        <div class="rounded-[1.4rem] border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                            Configure o link do mapa no modulo do rodape para exibir a localizacao aqui.
                        </div>
                    @endif
                    <div class="grid gap-3">
                        @forelse ($footerSocialLinks as $socialLink)
                            @if (filled(data_get($socialLink, 'url')))
                                <a href="{{ data_get($socialLink, 'url') }}" target="_blank" rel="noreferrer" class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm font-semibold text-white transition hover:bg-white/10">
                                    {{ data_get($socialLink, 'label') ?: data_get($socialLink, 'url') }}
                                </a>
                            @endif
                        @empty
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm text-slate-300">
                                Adicione Instagram, Facebook, YouTube ou outra rede no modulo do rodape.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="border-t" style="border-color: color-mix(in srgb, var(--site-primary) 12%, transparent);">
                <div class="mx-auto max-w-7xl px-4 py-5 text-sm text-slate-500 sm:px-6 lg:px-8">
                    &copy; {{ date('Y') }} {{ $footer['copyright_text'] ?: 'CERAPE. Todos os direitos reservados.' }}
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>

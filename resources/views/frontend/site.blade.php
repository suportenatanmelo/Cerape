<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="description" content="@yield('meta_description', 'Site institucional do CERAPE com blog, contato e conteudo gerenciado pelo Filament.')" />
        <title>@yield('title', config('app.name'))</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('head')
    </head>
    <body class="min-h-screen bg-[#071b12] text-[#f7f5ea] antialiased selection:bg-[#f2c94c] selection:text-[#071b12]">
        <div class="pointer-events-none fixed inset-0 overflow-hidden">
            <div class="absolute -left-24 top-0 h-72 w-72 rounded-full bg-[#f2c94c]/18 blur-3xl"></div>
            <div class="absolute right-0 top-24 h-96 w-96 rounded-full bg-[#2f6b45]/12 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-80 w-80 rounded-full bg-[#7a5733]/12 blur-3xl"></div>
        </div>

        <header class="sticky top-0 z-50 border-b border-[#d8c98f]/10 bg-[#071b12]/78 backdrop-blur-xl">
            <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl border border-[#d8c98f]/15 bg-white/5 shadow-lg shadow-black/20">
                        <img src="{{ asset('images/logo.png') }}" alt="CERAPE" class="h-8 w-8 rounded-xl object-cover" />
                    </span>
                    <div>
                        <div class="font-display text-lg font-bold tracking-tight text-white">CERAPE</div>
                        <div class="text-xs font-medium uppercase tracking-[0.3em] text-slate-400">Portal institucional</div>
                    </div>
                </a>

                <nav class="hidden items-center gap-2 lg:flex">
                    <a href="{{ route('home') }}" @class([
                        'rounded-full px-4 py-2 text-sm font-semibold transition',
                        'bg-white/10 text-white' => request()->routeIs('home'),
                        'text-slate-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('home'),
                    ])>Início</a>
                    <a href="{{ route('about') }}" @class([
                        'rounded-full px-4 py-2 text-sm font-semibold transition',
                        'bg-white/10 text-white' => request()->routeIs('about'),
                        'text-slate-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('about'),
                    ])>Sobre</a>
                    <a href="{{ route('blog') }}" @class([
                        'rounded-full px-4 py-2 text-sm font-semibold transition',
                        'bg-white/10 text-white' => request()->routeIs('blog') || request()->routeIs('blog.show'),
                        'text-slate-300 hover:bg-white/5 hover:text-white' => ! (request()->routeIs('blog') || request()->routeIs('blog.show')),
                    ])>Blog</a>
                    <a href="{{ route('contact') }}" @class([
                        'rounded-full px-4 py-2 text-sm font-semibold transition',
                        'bg-white/10 text-white' => request()->routeIs('contact'),
                        'text-slate-300 hover:bg-white/5 hover:text-white' => ! request()->routeIs('contact'),
                    ])>Contato</a>
                </nav>

                <div class="hidden items-center gap-3 lg:flex">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center rounded-full bg-[#f2c94c] px-5 py-2.5 text-sm font-bold text-[#071b12] shadow-lg shadow-[#f2c94c]/20 transition hover:bg-[#f5d76c]">
                        Falar com a equipe
                    </a>
                </div>

                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white transition hover:bg-white/10 lg:hidden"
                    data-site-nav-toggle
                    aria-controls="site-nav"
                    aria-expanded="false"
                >
                    Menu
                </button>
            </div>

            <div id="site-nav" class="hidden border-t border-[#d8c98f]/10 bg-[#071b12]/95 px-4 py-4 lg:hidden" data-site-nav>
                <div class="mx-auto flex max-w-7xl flex-col gap-2 sm:flex-row sm:flex-wrap">
                    <a href="{{ route('home') }}" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white">Início</a>
                    <a href="{{ route('about') }}" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white">Sobre</a>
                    <a href="{{ route('blog') }}" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white">Blog</a>
                    <a href="{{ route('contact') }}" class="rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-semibold text-white">Contato</a>
                </div>
            </div>
        </header>

        <main class="relative">
            @yield('content')
        </main>

        <footer class="border-t border-white/10 bg-slate-950/90">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1.2fr_0.8fr] lg:px-8">
                <div>
                    <div class="font-display text-2xl font-bold text-white">CERAPE</div>
                    <p class="mt-4 max-w-xl text-sm leading-7 text-slate-400">
                        Um frontend institucional pensado para comunicar com clareza, manter o conteudo organizado e facilitar o trabalho da equipe no Filament.
                    </p>
                </div>
                <div class="grid gap-4 sm:grid-cols-3">
                    <a href="{{ route('about') }}" class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm font-semibold text-white transition hover:bg-white/10">Sobre</a>
                    <a href="{{ route('blog') }}" class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm font-semibold text-white transition hover:bg-white/10">Blog</a>
                    <a href="{{ route('contact') }}" class="rounded-3xl border border-white/10 bg-white/5 p-4 text-sm font-semibold text-white transition hover:bg-white/10">Contato</a>
                </div>
            </div>
            <div class="border-t border-[#d8c98f]/10">
                <div class="mx-auto max-w-7xl px-4 py-5 text-sm text-slate-500 sm:px-6 lg:px-8">
                    &copy; {{ date('Y') }} CERAPE. Conteudo gerenciado pelo painel `/frontend`.
                </div>
            </div>
        </footer>

        @stack('scripts')
    </body>
</html>

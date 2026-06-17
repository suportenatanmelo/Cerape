@extends('frontend.site')

@php
    use App\Support\MapEmbedResolver;

    $contactData = is_object($contactPage ?? null) ? $contactPage->toArray() : (array) ($contactPage ?? []);
    $footerData = is_object($footerSettings ?? null) ? $footerSettings->toArray() : (array) ($footerSettings ?? []);
    $contactAddress = data_get($footerData, 'address') ?: data_get($contactData, 'address') ?: 'Fazenda - R. 5 A - Parque Alvorada III, Luziânia - GO, 72859-899';
    $contactMapUrl = MapEmbedResolver::src(
        data_get($footerData, 'map_embed_code')
            ?: data_get($footerData, 'map_embed_url')
            ?: data_get($contactData, 'map_embed_code')
            ?: data_get($contactData, 'map_embed_url'),
        $contactAddress
    );
@endphp

@section('title', 'CERAPE | Contato')
@section('meta_description', 'Pagina de contato do CERAPE com formulario e informacoes principais.')

@section('content')
    <section class="relative">
        <div class="mx-auto max-w-7xl px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-20 lg:pt-16">
            <div class="glass-card p-8 lg:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="space-y-5">
                        <span class="section-kicker">Contato</span>
                        <h1 class="font-display text-4xl font-bold tracking-tight text-slate-900 sm:text-5xl">Fale com a equipe</h1>
                        <p class="max-w-3xl text-lg leading-8 text-slate-600">Envie sua mensagem pelo formulario abaixo. O retorno fica organizado e o site continua simples de manter.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
            <div class="space-y-4">
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--site-primary)]">E-mail</div>
                    <div class="mt-3 text-xl font-display font-bold text-slate-900">{{ data_get($footerData, 'email') ?: data_get($contactData, 'email') ?: 'contato@cerape.local' }}</div>
                </div>
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--site-primary)]">Telefone</div>
                    <div class="mt-3 text-xl font-display font-bold text-slate-900">{{ data_get($footerData, 'phone') ?: data_get($contactData, 'phone') ?: '(00) 00000-0000' }}</div>
                </div>
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--site-primary)]">Endereco</div>
                    <div class="mt-3 text-xl font-display font-bold text-slate-900">{{ $contactAddress }}</div>
                </div>
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-[var(--site-primary)]">Localização</div>
                    <a
                        href="https://www.google.com/maps/search/?api=1&query={{ urlencode($contactAddress) }}"
                        target="_blank"
                        rel="noreferrer"
                        class="mt-3 inline-flex items-center justify-center rounded-full bg-[var(--site-primary)] px-5 py-3 text-sm font-bold text-white transition hover:opacity-90"
                    >
                        Abrir no Google Maps
                    </a>
                </div>
            </div>

            <div class="space-y-6">
                @include('frontend.partials.contact-form')
            </div>
        </div>
    </section>
@endsection

@extends('frontend.site')

@section('title', 'CERAPE | Contato')
@section('meta_description', 'Pagina de contato do CERAPE com formulario e informacoes principais.')

@section('content')
    <section class="relative">
        <div class="mx-auto max-w-7xl px-4 pb-16 pt-10 sm:px-6 lg:px-8 lg:pb-20 lg:pt-16">
            <div class="glass-card p-8 lg:p-10">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="space-y-5">
                        <span class="section-kicker">Contato</span>
                        <h1 class="font-display text-4xl font-bold tracking-tight text-white sm:text-5xl">Fale com a equipe</h1>
                        <p class="max-w-3xl text-lg leading-8 text-slate-300">Envie sua mensagem pelo formulario abaixo. O retorno fica organizado e o site continua simples de manter.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pb-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.85fr_1.15fr] lg:px-8">
            <div class="space-y-4">
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-amber-200">E-mail</div>
                    <div class="mt-3 text-xl font-display font-bold text-white">contato@cerape.local</div>
                </div>
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-cyan-200">Telefone</div>
                    <div class="mt-3 text-xl font-display font-bold text-white">(00) 00000-0000</div>
                </div>
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-emerald-200">Endereco</div>
                    <div class="mt-3 text-xl font-display font-bold text-white">CERAPE</div>
                </div>
                <div class="soft-panel p-6">
                    <div class="text-sm font-semibold uppercase tracking-[0.28em] text-sky-200">Painel</div>
                    <div class="mt-3 text-xl font-display font-bold text-white">/frontend</div>
                </div>
            </div>

            @include('frontend.partials.contact-form')
        </div>
    </section>
@endsection

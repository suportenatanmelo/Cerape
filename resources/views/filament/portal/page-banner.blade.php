@php
    $user = auth()->user();
    $isFamily = \App\Support\PortalContext::isFamilyUser($user);
    $acolhido = $isFamily ? $user?->acolhido : null;
@endphp

@if ($user && $isFamily)
    <section class="mb-6 overflow-hidden rounded-[2rem] border border-rose-200/80 bg-gradient-to-r from-rose-50 via-orange-50 to-amber-50 shadow-sm ring-1 ring-white/70 dark:border-rose-900/40 dark:from-rose-950/40 dark:via-gray-950 dark:to-amber-950/30">
        <div class="grid gap-6 px-6 py-6 lg:grid-cols-[minmax(0,1fr)_280px] lg:px-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em] text-rose-500 dark:text-rose-300">
                    Portal da Familia
                </p>
                <h1 class="mt-2 text-2xl font-semibold tracking-tight text-gray-950 dark:text-white">
                    Um espaco acolhedor para acompanhar quem voce ama
                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-gray-600 dark:text-gray-300">
                    Aqui voce visualiza apenas os dados autorizados pela equipe da Cerape, com mais clareza, organizacao e cuidado.
                </p>
            </div>

            <div class="rounded-[1.5rem] bg-white/80 p-4 shadow-sm ring-1 ring-rose-100 backdrop-blur dark:bg-white/5 dark:ring-white/10">
                <p class="text-[11px] font-medium uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">
                    Consulta atual
                </p>
                <p class="mt-2 text-lg font-semibold text-gray-950 dark:text-white">
                    {{ $acolhido?->nome_completo_paciente ?? 'Acolhido vinculado' }}
                </p>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                    Informacoes do acolhido disponiveis no menu lateral.
                </p>
            </div>
        </div>
    </section>
@endif

<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-3xl border border-slate-200 bg-white/95 p-6 shadow-sm shadow-slate-200/40">
            <h2 class="text-2xl font-semibold text-slate-900">Gerador de acolhidos</h2>
            <p class="mt-3 max-w-3xl text-sm leading-6 text-slate-600">
                Selecione um acolhido para gerar um PDF com o checklist dos campos de cadastro habilitados.
                O arquivo inclui os campos Nome completo, CPF, Data de nascimento e Data do acolhimento.
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-[minmax(18rem,24rem)_minmax(0,1fr)]">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/40">
                {{ $this->form }}
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm shadow-slate-200/40">
                <h3 class="text-lg font-semibold text-slate-900">PDF de saída</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Use o botão <strong>Baixar PDF</strong> no topo para gerar o checklist em PDF.
                    O documento será baixado com o cadastro do acolhido escolhido.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>

<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        <div class="rounded-3xl border border-[--gray-200] bg-white p-6 shadow-sm">
            <h2 class="text-2xl font-semibold tracking-tight text-gray-950">Configuração do WhatsApp</h2>
            <p class="mt-2 text-sm text-gray-600">
                Aqui você define o número, a mensagem automática e o e-mail exibidos no site público.
            </p>

            <div class="mt-6">
                {{ $this->form }}
            </div>

            <div class="mt-6 flex justify-end">
                <x-filament::button wire:click="save" color="warning">
                    Salvar
                </x-filament::button>
            </div>
        </div>

        <div class="rounded-3xl border border-[--gray-200] bg-gradient-to-b from-emerald-50 to-white p-6 shadow-sm">
            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-emerald-800">Prévia</span>
            <h3 class="mt-3 text-2xl font-semibold tracking-tight text-gray-950">Como o botão aparece no site</h3>

            <div class="mt-4 rounded-2xl border border-emerald-100 bg-white p-4">
                <p class="text-sm leading-6 text-gray-700">
                    <strong>Número:</strong> {{ $data['whatsapp_number'] ?? '(91) 99999-9999' }}
                </p>
                <p class="mt-2 text-sm leading-6 text-gray-700">
                    <strong>Mensagem:</strong> {{ $data['whatsapp_message'] ?? 'Olá, gostaria de mais informações.' }}
                </p>
                <p class="mt-2 text-sm leading-6 text-gray-700">
                    <strong>E-mail:</strong> {{ $data['site_email'] ?? 'contato@cerape.com.br' }}
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>

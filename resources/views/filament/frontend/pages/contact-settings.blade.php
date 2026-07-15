<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-[1.1fr_0.9fr]">
        <div class="rounded-3xl border border-[--gray-200] bg-white p-6 shadow-sm">
            <h2 class="text-2xl font-semibold tracking-tight text-gray-950">Configuração do contato</h2>
            <p class="mt-2 text-sm text-gray-600">
                Edite aqui o conteúdo da seção de contato exibida no site público.
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

        <div class="rounded-3xl border border-[--gray-200] bg-gradient-to-b from-amber-50 to-white p-6 shadow-sm">
            <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.16em] text-amber-800">Prévia</span>
            <h3 class="mt-3 text-2xl font-semibold tracking-tight text-gray-950">{{ $data['contact_title'] ?? 'Vamos conversar' }}</h3>
            <p class="mt-2 text-sm leading-6 text-gray-700">{{ $data['contact_description'] ?? 'Toda mensagem é tratada com sigilo. Nossa equipe responde em até 24h.' }}</p>

            <div class="mt-5 space-y-3 rounded-2xl border border-amber-100 bg-white p-4 text-sm text-gray-700">
                <p><strong>{{ $data['contact_phone_label'] ?? 'Telefone' }}:</strong> {{ $data['contact_phone_line'] ?? '(11) 0000-0000 · WhatsApp 24h' }}</p>
                <p><strong>{{ $data['contact_address_label'] ?? 'Endereço' }}:</strong> {{ $data['contact_address_line'] ?? 'Rua das Acácias, 120 — Bairro Jardim, São Paulo/SP' }}</p>
                <p><strong>{{ $data['contact_email_label'] ?? 'E-mail' }}:</strong> {{ $data['contact_email_line'] ?? 'contato@cerape.com' }}</p>
            </div>
        </div>
    </div>
</x-filament-panels::page>

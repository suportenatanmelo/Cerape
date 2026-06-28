<x-filament-panels::page>
    <div class="grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
        <div class="rounded-3xl border border-[--gray-200] bg-white p-6 shadow-sm">
            <h2 class="text-2xl font-semibold tracking-tight text-gray-950">Informações da clínica</h2>
            <p class="mt-2 text-sm text-gray-600">
                Aqui você edita o bloco “Informações” que aparece na home do site.
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
            <h3 class="mt-3 text-2xl font-semibold tracking-tight text-gray-950">{{ $data['clinic_contact_title'] ?? 'Informações' }}</h3>
            <div class="mt-4 space-y-3 rounded-2xl border border-emerald-100 bg-white p-4 text-sm text-gray-700">
                <p><strong>{{ $data['clinic_contact_name'] ?? 'Clínica CERAPE' }}</strong></p>
                <p><strong>{{ $data['clinic_contact_address_label'] ?? 'Endereço' }}:</strong> {{ $data['clinic_contact_address_line'] ?? 'Rua das Acácias, 120 — Bairro Jardim, São Paulo/SP' }}</p>
                <p><strong>{{ $data['clinic_contact_city_label'] ?? 'Cidade' }}:</strong> {{ $data['clinic_contact_city_line'] ?? 'São Paulo' }}</p>
                <p><strong>{{ $data['clinic_contact_state_label'] ?? 'Estado' }}:</strong> {{ $data['clinic_contact_state_line'] ?? 'SP' }}</p>
                <p><strong>{{ $data['clinic_contact_zip_label'] ?? 'CEP' }}:</strong> {{ $data['clinic_contact_zip_line'] ?? '00000-000' }}</p>
                <p><strong>{{ $data['clinic_contact_phone_label'] ?? 'Telefone' }}:</strong> {{ $data['clinic_contact_phone_line'] ?? '(11) 0000-0000' }}</p>
                <p><strong>{{ $data['clinic_contact_email_label'] ?? 'E-mail' }}:</strong> {{ $data['clinic_contact_email_line'] ?? 'contato@cerape.com' }}</p>
            </div>
        </div>
    </div>
</x-filament-panels::page>

<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">Extrato por acolhido</x-slot>

        <div>
            <label class="block text-sm font-medium text-gray-700">Escolha o acolhido</label>
            <select id="acolhido-select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">-- selecione --</option>
                @foreach($acolhidos as $a)
                    <option value="{{ $a->id }}">{{ $a->nome_completo_paciente }}</option>
                @endforeach
            </select>

            <div class="mt-3 flex gap-2">
                <button id="open-extrato" class="inline-flex items-center px-3 py-1 bg-amber-500 text-white rounded">Ver extrato</button>
                <a href="/admin/extrato-financeiro" class="inline-flex items-center px-3 py-1 bg-gray-100 rounded">Abrir extratos</a>
            </div>
        </div>

        <script>
            (function(){
                const btn = document.getElementById('open-extrato');
                const sel = document.getElementById('acolhido-select');
                btn?.addEventListener('click', function(){
                    const id = sel?.value;
                    if (!id) {
                        alert('Selecione um acolhido.');
                        return;
                    }
                    const url = '/admin/extrato-financeiro?acolhido_id=' + encodeURIComponent(id);
                    window.location.href = url;
                });
            })();
        </script>
    </x-filament::section>
</x-filament-widgets::widget>

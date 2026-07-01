@php
    use Illuminate\Support\Facades\Storage;

    $items = $items ?? collect();
@endphp

<x-filament::page>
    <x-slot name="header">
        <h2 class="text-lg font-medium">Lixeira do Carrossel</h2>
    </x-slot>

    <div class="space-y-4">
        @if($items->isEmpty())
            <div class="p-4 text-sm">A lixeira está vazia.</div>
        @else
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-4 py-2">Preview</th>
                        <th class="px-4 py-2">Título</th>
                        <th class="px-4 py-2">Excluído em</th>
                        <th class="px-4 py-2">Excluído por</th>
                        <th class="px-4 py-2">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr class="border-t">
                            <td class="px-4 py-2">
                                @if($item->image_path)
                                    @php
                                        $src = $item->image_path;

                                        if (! str_starts_with($src, ['http://','https://','//','data:','/'])) {
                                            $src = route('media.serve', ['path' => ltrim($src, '/')]);
                                        }

                                    @endphp
                                    <img src="{{ $src }}" alt="preview" style="max-width:140px; max-height:80px; object-fit:cover;" />
                                @else
                                    <span class="text-sm text-gray-500">Sem imagem</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $item->title ?? '-' }}</td>
                            <td class="px-4 py-2">{{ optional($item->deleted_at)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2">@if($item->deleted_by) {{ \App\Models\User::find($item->deleted_by)?->name ?? $item->deleted_by }} @else - @endif</td>
                            <td class="px-4 py-2">
                                <form method="POST" action="{{ route('admin.hero-slide-trash.restore', ['id' => $item->id]) }}" style="display:inline" onsubmit="return confirm('Restaurar este slide?');">
                                    @csrf
                                    <button type="submit" class="filament-button filament-button-size-sm">Restaurar</button>
                                </form>

                                <form method="POST" action="{{ route('admin.hero-slide-trash.delete', ['id' => $item->id]) }}" style="display:inline;margin-left:8px;" onsubmit="return confirm('Excluir definitivamente este slide? Esta ação não pode ser desfeita.');">
                                    @csrf
                                    <button type="submit" class="filament-button filament-button-size-sm filament-button-color-danger">Excluir definitivamente</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pt-4">
                <form method="POST" action="{{ route('admin.hero-slide-trash.empty') }}" onsubmit="return confirm('Limpar definitivamente a lixeira? Esta ação removerá todos os itens permanentemente.');">
                    @csrf
                    <button type="submit" class="filament-button filament-button-size-md filament-button-color-danger">Limpar definitivamente a lixeira</button>
                </form>
            </div>
        @endif
    </div>

</x-filament::page>

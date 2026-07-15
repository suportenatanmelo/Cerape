@php
    $url = \App\Filament\Pages\ExtratoFinanceiro::getUrl([
        'acolhido_id' => $record->id,
    ], panel: 'admin');
@endphp

<x-filament::button
    tag="a"
    :href="$url"
    icon="heroicon-o-arrow-top-right-on-square"
    color="primary"
>
    Ver extrato completo
</x-filament::button>

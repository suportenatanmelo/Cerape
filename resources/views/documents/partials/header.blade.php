<div class="document-letterhead">
    @if (filled($documentBranding['letterhead'] ?? null))
        <img src="{{ $documentBranding['letterhead'] }}" alt="Papel timbrado CERAPE">
    @endif
</div>
@if (filled($documentBranding['logo'] ?? null))
    <img src="{{ $documentBranding['logo'] }}" alt="Logotipo CERAPE" style="height: 29mm; left: 15mm; object-fit: contain; position: fixed; top: 10mm; width: 29mm; z-index: 2;">
@endif

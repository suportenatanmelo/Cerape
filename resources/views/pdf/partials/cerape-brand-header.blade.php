<div class="brand-header">
    <div class="brand-logo-box">
        @php($brandLogo = \App\Support\PdfImage::publicDataUri(\App\Support\SystemBranding::logoPublicPath()) ?? \App\Support\SystemBranding::logoUrl())
        <img class="brand-logo" src="{{ $brandLogo }}" alt="Logo CERAPE">
    </div>

    <div class="brand-text">
        <div class="brand-header-title">CENTRO DE REABILITAÇÃO DO PRESO E EGRESSO - CERAPE</div>
    </div>

    <div class="brand-spacer" aria-hidden="true"></div>
</div>

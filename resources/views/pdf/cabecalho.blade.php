<div class="brand-header" role="banner" aria-label="Cabeçalho institucional CERAPE">
    <div class="brand-logo-box">
        @php($brandLogo = \App\Support\PdfImage::publicDataUri(\App\Support\SystemBranding::logoPublicPath()) ?? \App\Support\SystemBranding::logoUrl())
        <img class="brand-logo" src="{{ $brandLogo }}" alt="Logo CERAPE">
    </div>

    <div class="brand-header-copy">
        <div class="brand-header-title">CENTRO DE REABILITAÇÃO DO PRESO E EGRESSO - CERAPE</div>
        <div class="brand-header-line">Fones (61) 9.9320-8741 • e-mail: cerape1995@gmail.com</div>
    </div>
</div>

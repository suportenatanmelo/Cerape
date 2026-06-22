<div class="brand-header">
    <div class="brand-logo-box">
        @php($brandLogo = \App\Support\PdfImage::publicDataUri('images/logo.png') ?? asset('images/logo-pdf.svg'))
        <img class="brand-logo" src="{{ $brandLogo }}" alt="CERAPE">
    </div>

    <div class="brand-header-text">
        <div class="brand-header-title">CENTRO DE REABILITAÇÃO DO PRESO E EGRESSO (CERAPE)</div>
        <div class="brand-header-subtitle">Fones: (61) 3323-5403 / (61) 9.9320-8741</div>
        <div class="brand-header-subtitle">e-mail: cerape1995@gmail.com</div>
    </div>
</div>

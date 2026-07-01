<style>
    * { box-sizing: border-box; }
    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100%;
        background: #ffffff;
        color: #111111;
        font-family: DejaVu Serif, serif;
        font-size: 12pt;
        line-height: 1.5;
        text-align: justify;
    }
    @page { margin: 30mm 20mm 20mm 30mm; }
    .page { position: relative; min-height: 100vh; background: #ffffff; }
    .document-body { width: 100%; margin: 0 auto; padding-top: 58px; padding-bottom: 54px; }
    .brand-header {
        position: fixed;
        top: 16mm;
        left: 30mm;
        right: 20mm;
        width: auto;
        display: flex;
        align-items: center;
        gap: 14px;
        text-align: center;
        padding-bottom: 3.5mm;
        border-bottom: 1px solid #111111;
    }
    .brand-logo-box,
    .brand-spacer { flex: 0 0 92px; width: 92px; }
    .brand-logo { display: block; width: 92px; height: 92px; object-fit: contain; }
    .brand-text { flex: 1; text-align: center; }
    .brand-header-title { font-size: 12pt; font-weight: 700; letter-spacing: 0.02em; line-height: 1.2; margin: 0; text-transform: uppercase; }
    .brand-header-subtitle {
        font-size: 9pt;
        font-weight: 400;
        letter-spacing: 0.01em;
        line-height: 1.25;
        margin: 1.5mm 0 0;
        text-transform: none;
    }
    .report-header {
        border-bottom: 1px solid #111111;
        margin-bottom: 16px;
        padding-bottom: 10px;
    }
    .report-eyebrow {
        color: #111111;
        font-size: 9pt;
        font-weight: 700;
        letter-spacing: 0.04em;
        margin-bottom: 2px;
        text-transform: uppercase;
    }
    .report-title {
        color: #111827;
        font-size: 14pt;
        line-height: 1.2;
        margin: 0 0 6px;
        text-align: center;
    }
    .report-title--compact { font-size: 13pt; }
    .report-subtitle {
        color: #333333;
        font-size: 10pt;
        line-height: 1.4;
        margin: 0 0 3px;
    }
    .report-section-title {
        color: #111111;
        font-size: 11pt;
        font-weight: 700;
        margin: 14px 0 8px;
        text-transform: uppercase;
    }
    .report-section-title--soft {
        background: transparent;
        border-left: 0;
        color: #111827;
        font-size: 11pt;
        margin: 0;
        padding: 0 0 6px;
        text-transform: uppercase;
    }
    .report-muted { color: #6b7280; }
    .report-badge {
        border-radius: 0;
        display: inline-block;
        font-size: 9pt;
        font-weight: 700;
        margin-top: 6px;
        padding: 2px 8px;
        text-transform: uppercase;
    }
    .report-badge--teal { background: transparent; color: #111111; }
    .report-badge--amber { background: transparent; color: #111111; }
    .cerape-footer-wrapper {
        position: fixed;
        left: 30mm;
        right: 20mm;
        bottom: 12mm;
        width: auto;
    }
    .cerape-footer-frame { border-top: 1px solid #111111; padding-top: 3mm; }
    .cerape-footer-columns { display: block; }
    .cerape-footer-column { display: none; }
    .footer-address-label { display: none; }
    .cerape-footer-cnpj { display: none; }
    .cerape-footer-divider { display: none; }
    .cerape-footer-mission {
        font-size: 8.5pt;
        font-weight: 400;
        letter-spacing: 0.01em;
        line-height: 1.3;
        padding: 0;
        text-align: center;
        text-transform: none;
    }
    .page-break { page-break-after: always; }
    table { border-collapse: collapse; width: 100%; }
    th, td, p, li, div { overflow-wrap: break-word; word-break: break-word; }
    img { max-width: 100%; height: auto; }
    p { margin: 0 0 8px; }
</style>

<style>
    * { box-sizing: border-box; }
    html, body {
        margin: 0;
        padding: 0;
        width: 100%;
        min-height: 100%;
        background: #ffffff;
        color: #111827;
        font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
        font-size: 11pt;
        line-height: 1.35;
        text-align: justify;
    }
    @page { margin: 18mm 14mm 16mm 18mm; }
    body { position: relative; }
    .page { position: relative; width: 100%; min-height: 100%; }
    .brand-header {
        position: fixed;
        top: 8mm;
        left: 18mm;
        right: 14mm;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 4px;
        border-bottom: 1px solid #111827;
        background: transparent;
        z-index: 20;
    }
    .brand-logo-box { flex: 0 0 78px; width: 78px; }
    .brand-logo { display: block; width: 78px; height: auto; object-fit: contain; }
    .brand-header-copy { flex: 1; }
    .brand-header-title { font-size: 11pt; font-weight: 700; letter-spacing: 0.02em; line-height: 1.1; margin: 0 0 1px; text-transform: uppercase; }
    .brand-header-line { font-size: 8pt; font-weight: 600; line-height: 1.25; color: #1f2937; }
    .document-body { width: 100%; margin: 0 auto; padding-top: 44mm; padding-bottom: 36mm; }
    .report-header { margin-bottom: 10px; }
    .report-eyebrow { color: #111827; font-size: 8pt; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; margin-bottom: 3px; }
    .report-title { color: #111827; font-size: 13pt; font-weight: 700; line-height: 1.2; margin: 0 0 5px; }
    .report-title--compact { font-size: 12pt; }
    .report-subtitle { color: #374151; font-size: 9pt; margin: 0 0 3px; }
    .report-section-title { color: #111827; font-size: 10pt; font-weight: 700; margin: 12px 0 6px; text-transform: uppercase; }
    .report-section-title--soft { background: #f8fafc; border-left: 4px solid #0f766e; color: #0f172a; font-size: 9pt; font-weight: 700; margin: 10px 0 6px; padding: 6px 10px; text-transform: uppercase; }
    .report-muted { color: #6b7280; }
    .report-badge { display: inline-block; font-size: 8pt; font-weight: 700; margin-top: 4px; padding: 2px 6px; text-transform: uppercase; }
    .report-badge--teal { color: #111827; }
    .report-badge--amber { color: #111827; }
    .page-number { position: fixed; bottom: 10mm; right: 14mm; font-size: 8.5pt; color: #4b5563; z-index: 20; }
    .page-number:after { content: "Página " counter(page) " de " counter(pages); }
    .cerape-footer-wrapper { position: fixed; left: 18mm; right: 14mm; bottom: 6mm; width: auto; padding-top: 3mm; z-index: 20; font-size: 7.5pt; line-height: 1.2; }
    .cerape-footer-blocks { display: table; width: 100%; table-layout: fixed; border-top: 1px solid #111827; padding-top: 4px; margin-bottom: 3px; }
    .cerape-footer-block { display: table-cell; width: 33.333%; vertical-align: top; padding-right: 8px; }
    .cerape-footer-block:last-child { padding-right: 0; }
    .cerape-footer-section-title { font-size: 7.5pt; font-weight: 700; margin-bottom: 2px; color: #111827; text-transform: uppercase; letter-spacing: 0.02em; }
    .cerape-footer-block div { font-size: 7.5pt; line-height: 1.2; margin-bottom: 1px; color: #111827; }
    .cerape-footer-block div:last-child { margin-bottom: 0; }
    .cerape-footer-divider { border-top: 1px solid #111827; margin: 1px 0 3px; }
    .cerape-footer-tagline { text-align: center; font-size: 7.5pt; font-weight: 700; letter-spacing: 0.12em; text-transform: uppercase; color: #111827; margin: 0; padding: 0; }
    .page-break { page-break-after: always; }
    table { border-collapse: collapse; width: 100%; }
    th, td, p, li, div { overflow-wrap: break-word; word-break: break-word; }
    th, td { border: 1px solid #d1d5db; padding: 6px 8px; vertical-align: top; }
    th { background: #f8fafc; font-weight: 700; text-transform: uppercase; }
    td { font-size: 10pt; }
    .pdf-section { margin-bottom: 14px; page-break-inside: avoid; }
    .pdf-section-title { font-size: 11pt; font-weight: 700; margin: 0 0 8px; }
    .pdf-label { font-size: 9pt; font-weight: 700; color: #1f2937; }
    .pdf-value { font-size: 10pt; color: #111827; }
    .pdf-text { font-size: 10pt; line-height: 1.5; }
    .pdf-table, .pdf-data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
    .pdf-table th, .pdf-table td, .pdf-data-table th, .pdf-data-table td { border: 1px solid #d1d5db; padding: 6px 8px; }
    .pdf-table th, .pdf-data-table th { background: #f8fafc; font-weight: 700; }
    .pdf-signature { margin-top: 28px; }
    .pdf-observation { font-size: 10pt; color: #4b5563; }
    .page-break { page-break-after: always; }
    img { max-width: 100%; height: auto; }
    p { margin: 0 0 8px; }
</style>

<style>
    @page { size: A4; margin: 0; }
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; }
    body {
        color: #1f2937;
        font-family: Arial, DejaVu Sans, sans-serif;
        font-size: 10.5pt;
        line-height: 1.5;
    }
    .document-letterhead {
        bottom: 0;
        left: 0;
        position: fixed;
        right: 0;
        top: 0;
        z-index: 0;
    }
    .document-letterhead img {
        height: 100%;
        width: 100%;
    }
    .document-page {
        margin: 0;
        min-height: 297mm;
        padding: 49mm 25mm 43mm 30mm;
        position: relative;
    }
    .document-content { position: relative; z-index: 1; }
    .document-page-number {
        bottom: 5mm;
        color: #374151;
        font-size: 8pt;
        position: fixed;
        right: 25mm;
        text-align: right;
        z-index: 2;
    }
    .document-title {
        color: #111827;
        font-size: 16pt;
        font-weight: bold;
        letter-spacing: .3px;
        margin: 0 0 6mm;
        text-align: center;
        text-transform: uppercase;
    }
    .document-subtitle { color: #4b5563; margin: -3mm 0 6mm; text-align: center; }
    .document-meta {
        border-bottom: 1px solid #d1d5db;
        border-top: 1px solid #d1d5db;
        color: #4b5563;
        font-size: 9pt;
        margin: 0 0 7mm;
        padding: 3mm 0;
    }
    .document-section { page-break-inside: avoid; }
    .document-section-title {
        border-bottom: 1.5px solid #1f3b72;
        color: #1f3b72;
        font-size: 11pt;
        font-weight: bold;
        margin: 7mm 0 3mm;
        padding-bottom: 1.5mm;
        text-transform: uppercase;
    }
    p { margin: 0 0 4mm; text-align: justify; }
    table { border-collapse: collapse; margin: 0 0 6mm; page-break-inside: auto; width: 100%; }
    thead { display: table-header-group; }
    tr { page-break-inside: avoid; }
    th, td { border: .5px solid #cbd5e1; padding: 2.5mm 3mm; text-align: left; vertical-align: top; }
    th { background: #eef2f7; color: #1f2937; font-weight: bold; }
    .document-signatures { page-break-inside: avoid; margin-top: 16mm; }
    .document-signature { display: inline-block; text-align: center; vertical-align: top; width: 48%; }
    .document-signature-line { border-top: 1px solid #374151; margin: 0 auto 2mm; width: 70%; }
    .document-note { color: #6b7280; font-size: 8.5pt; }
    .document-page-number:after { content: 'Página ' counter(page); }
    .page-break { page-break-before: always; }
</style>

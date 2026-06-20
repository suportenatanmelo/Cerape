<style>
    * { box-sizing: border-box; }
    html, body { margin: 0; padding: 0; width: 100%; min-height: 100%; background: #ffffff; color: #111111; font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.45; }
    @page { margin: 0; }
    body { text-align: center; }
    .page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 26px 28px 18px;
        background: #ffffff;
    }
    .document-body { width: 100%; margin: 0 auto; text-align: left; flex: 1; padding: 18px 0 16px; }
    .brand-header {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 28px;
        margin-top: 4px;
        width: 100%;
    }
    .brand-logo-box { display: flex; align-items: center; justify-content: center; width: 128px; min-height: 128px; padding: 0; border: none; background: transparent; flex: 0 0 auto; }
    .brand-logo { display: block; max-width: 100%; max-height: 128px; width: auto; height: auto; }
    .brand-header-text { flex: 1; text-align: center; }
    .brand-header-title { font-size: 15px; font-weight: 400; line-height: 1.35; margin-bottom: 6px; color: #111111; text-transform: uppercase; }
    .brand-header-subtitle { color: #111111; font-size: 12px; line-height: 1.45; margin-top: 0; }
    .section { background: #ffffff; border: 1px solid #dbe4ea; border-radius: 12px; margin-bottom: 14px; overflow: hidden; page-break-inside: avoid; }
    .section-title { color: #1d4ed8; font-size: 14px; margin: 0 0 10px; padding: 10px 0; }
    .cerape-footer-wrapper { background: #ffffff; color: #111111; font-family: DejaVu Sans, sans-serif; margin-top: 0; padding: 0; page-break-inside: avoid; text-align: center; }
    .cerape-footer-container { display: flex; flex-direction: column; align-items: stretch; justify-content: center; gap: 10px; }
    .cerape-footer-logo { width: 100%; max-width: 102px; height: 102px; margin: 0 auto 0; }
    .cerape-footer-text { color: #111111; font-size: 14px; font-weight: 400; letter-spacing: 0; margin-bottom: 0; text-transform: none; }
    .cerape-footer-motto { color: #111111; font-size: 10px; font-weight: 400; letter-spacing: 0; }
    .cerape-footer-info { border-top: 0; border-bottom: 0; font-size: 8px; line-height: 1.25; margin-bottom: 0; padding: 0; text-align: left; }
    .cerape-footer-addresses {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-bottom: 12px;
    }
    .footer-address-item { display: block; margin-bottom: 0; }
    .footer-address-label { font-weight: bold; display: block; }
    .cerape-footer-cnpj { display: flex; justify-content: center; gap: 24px; margin-top: 6px; font-size: 8px; }
    .cerape-footer-divider { border-bottom: 3px solid #1e3a8a; margin: 10px 0 8px; }
    .cerape-footer-mission { color: #111111; font-size: 9px; font-weight: 400; letter-spacing: 0; line-height: 1.35; text-align: center; text-transform: uppercase; }
    table { border-collapse: collapse; width: 100%; page-break-inside: auto; }
    tr { page-break-inside: avoid; page-break-after: auto; }
    th, td { vertical-align: top; }
    img { max-width: 100%; height: auto; }
    .text-center { text-align: center; }
    .muted { color: #475569; }
    .badge { display: inline-block; border-radius: 999px; padding: 4px 10px; font-size: 10px; font-weight: bold; }
    .status-active { background: #dcfce7; color: #166534; }
    .status-inactive { background: #fee2e2; color: #991b1b; }
</style>

@php
    $settings ??= null;

    $activePalette = \App\Models\ThemePalette::query()->where('is_current', true)->first();

    if ($activePalette) {
        $headerPrimary = $activePalette->header_color ?? $settings?->header_primary_color ?? '#0f172a';
        $headerSecondary = $activePalette->secondary_color ?? $settings?->header_secondary_color ?? '#155e75';
        $footerPrimary = $activePalette->footer_color ?? $settings?->footer_primary_color ?? '#111827';
        $footerSecondary = $activePalette->secondary_color ?? $settings?->footer_secondary_color ?? '#0f766e';
        $fontColor = $activePalette->text_color ?? $settings?->font_color ?? '#e5e7eb';
        $accent = $activePalette->accent_color ?? $settings?->accent_color ?? '#38bdf8';
        $bg = $activePalette->background_color ?? '#faf7f2';
        $bgSoft = $activePalette->card_color ?? '#f1ece2';
        $surface = $activePalette->surface_color ?? 'rgba(255, 255, 255, 0.82)';
        $surfaceStrong = $activePalette->card_color ?? '#ffffff';
        $pine = $headerPrimary;
        $pineLight = $headerSecondary;
        $amber = $accent;
        $amberSoft = $accent;
        $sage = $footerSecondary;
        $ink = $fontColor;
        $inkSoft = $fontColor;
        $line = $activePalette->border_color ?? '#e2dbcb';
        $shadow = '0 18px 36px rgba(30, 61, 54, 0.08)';
        $paletteName = $activePalette->name;
    } else {
        $headerPrimary = $settings?->header_primary_color ?? '#0f172a';
        $headerSecondary = $settings?->header_secondary_color ?? '#155e75';
        $footerPrimary = $settings?->footer_primary_color ?? '#111827';
        $footerSecondary = $settings?->footer_secondary_color ?? '#0f766e';
        $fontColor = $settings?->font_color ?? '#e5e7eb';
        $accent = $settings?->accent_color ?? '#38bdf8';
        $paletteName = $paletteName ?? null;
        $bg = '#faf7f2';
        $bgSoft = '#f1ece2';
        $surface = 'rgba(255, 255, 255, 0.82)';
        $surfaceStrong = '#ffffff';
        $pine = '#1e3d36';
        $pineLight = '#2c5a4f';
        $amber = '#e08e4f';
        $amberSoft = '#f2c49a';
        $sage = '#6b8e78';
        $ink = '#2b2823';
        $inkSoft = '#6b6459';
        $line = '#e2dbcb';
        $shadow = '0 18px 36px rgba(30, 61, 54, 0.08)';
    }

    $whatsappDigits = preg_replace('/\D+/', '', (string) ($settings?->whatsapp_number ?? ''));
    $whatsappDigits = $whatsappDigits ? (str_starts_with($whatsappDigits, '55') ? $whatsappDigits : '55'.$whatsappDigits) : '';
    $whatsappMessage = trim((string) ($settings?->whatsapp_message ?? 'Olá, gostaria de mais informações.'));
    $whatsappWelcome = trim((string) ($settings?->contact_section_description ?? 'Toda mensagem é tratada com sigilo. Nossa equipe responde em até 24h.'));
    $whatsappUrl = $whatsappDigits ? 'https://wa.me/'.$whatsappDigits.'?text='.urlencode($whatsappMessage) : null;
    $siteLogoUrl = $settings?->logo_path
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($settings->logo_path)
        : asset('logo.png');
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $settings?->brand_name ?? 'CERAPE' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: {{ $bg ?? '#faf7f2' }};
            --bg-soft: {{ $bgSoft ?? '#f1ece2' }};
            --surface: {{ $surface ?? 'rgba(255, 255, 255, 0.82)' }};
            --surface-strong: {{ $surfaceStrong ?? '#ffffff' }};
            --pine: {{ $pine ?? '#1e3d36' }};
            --pine-light: {{ $pineLight ?? '#2c5a4f' }};
            --amber: {{ $amber ?? '#e08e4f' }};
            --amber-soft: {{ $amberSoft ?? '#f2c49a' }};
            --sage: {{ $sage ?? '#6b8e78' }};
            --ink: {{ $ink ?? '#2b2823' }};
            --ink-soft: {{ $inkSoft ?? '#6b6459' }};
            --line: {{ $line ?? '#e2dbcb' }};
            --shadow: {{ $shadow ?? '0 18px 36px rgba(30, 61, 54, 0.08)' }};
        }
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(242, 196, 154, 0.26), transparent 28%),
                radial-gradient(circle at bottom right, rgba(107, 142, 120, 0.16), transparent 24%),
                var(--bg);
        }
        h1, h2, h3 {
            font-family: 'Fraunces', serif;
            font-weight: 600;
            color: var(--pine);
            letter-spacing: -0.01em;
            margin-top: 0;
        }
        p { color: var(--ink-soft); line-height: 1.7; }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        .wrap { width: min(1180px, calc(100% - 32px)); margin: 0 auto; }
        .reveal {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity .8s cubic-bezier(.16,.84,.32,1), transform .8s cubic-bezier(.16,.84,.32,1);
            transition-delay: var(--reveal-delay, 0s);
        }
        .reveal.in-view {
            opacity: 1;
            transform: translateY(0);
        }
        @media (prefers-reduced-motion: reduce) {
            .reveal {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
            }
        }
        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            background: rgba(250, 247, 242, 0.92);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--line);
        }
        .topbar .wrap {
            display:flex;
            gap:18px;
            justify-content:space-between;
            align-items:center;
            padding: 16px 0;
            flex-wrap: wrap;
        }
        .brand {
            display:flex;
            align-items:center;
            gap:12px;
            font-weight: 800;
            letter-spacing: .06em;
            color: var(--pine);
        }
        .brand-logo {
            width: auto;
            height: 82px;
            max-width: 240px;
            object-fit: contain;
            box-shadow: 0 8px 18px rgba(30, 61, 54, 0.18);
            transition: transform .5s cubic-bezier(.34,1.56,.64,1);
        }
        .brand:hover .brand-logo {
            transform: rotate(8deg) scale(1.06);
        }
        .brand span { color: var(--amber); }
        nav { display:flex; gap:10px; flex-wrap:wrap; }
        nav a {
            padding: 9px 13px;
            border-radius: 999px;
            background: rgba(255,255,255,.62);
            border: 1px solid var(--line);
            color: var(--ink);
            font-size: .92rem;
            font-weight: 600;
        }
        nav a:hover { background: var(--pine); border-color: var(--pine); color: #fff; }
        main { padding: 34px 0 60px; }
        .hero { padding: 28px 0 18px; }
        .hero-cover {
            position: relative;
            min-height: 88vh;
            padding: 0;
            overflow: hidden;
            border-radius: 28px;
            border: 1px solid var(--line);
            box-shadow: 0 24px 70px rgba(30, 61, 54, .12);
            background: #000;
        }
        .hero-carousel { position:absolute; inset:0; }
        .hero-carousel .slide {
            position:absolute;
            inset:0;
            background-size:cover;
            background-position:center;
            opacity:0;
            transition:opacity 1.2s ease;
        }
        .hero-carousel .slide.active { opacity:1; }
        .hero-carousel .slide::after {
            content:'';
            position:absolute;
            inset:0;
            background:linear-gradient(180deg, rgba(20,40,35,.25) 0%, rgba(20,40,35,.66) 100%);
        }
        .arrow {
            position:absolute;
            top:50%;
            transform:translateY(-50%);
            z-index:3;
            background:rgba(255,255,255,.14);
            border:1px solid rgba(255,255,255,.3);
            color:#fff;
            width:44px;
            height:44px;
            border-radius:50%;
            cursor:pointer;
            font-size:1.1rem;
            display:flex;
            align-items:center;
            justify-content:center;
            backdrop-filter:blur(4px);
        }
        .arrow:hover { background:rgba(255,255,255,.28); }
        .arrow.prev { left:24px; }
        .arrow.next { right:24px; }
        .hero-content {
            position:relative;
            z-index:2;
            min-height:88vh;
            display:flex;
            flex-direction:column;
            justify-content:center;
            max-width:720px;
            margin:0 auto;
            padding: 92px 32px 60px;
            color:#fff;
        }
        .hero-title {
            color:#fff;
            font-size:clamp(2.4rem, 6vw, 4.8rem);
            margin: 16px 0 12px;
            line-height: 1.1;
            letter-spacing: -0.03em;
        }
        .line-mask { display:block; overflow:hidden; padding-bottom:.08em; }
        .line-inner {
            display:block;
            transform:translateY(115%);
            animation:lineUp 1s cubic-bezier(.16,.84,.32,1) forwards;
            animation-delay:var(--d,0s);
        }
        @keyframes lineUp { to { transform:translateY(0); } }
        .hero-content .eyebrow,
        .hero-content h1,
        .hero-content p,
        .hero-content .hero-actions {
            opacity: 0;
            animation: heroFadeUp .9s cubic-bezier(.16,.84,.32,1) forwards;
            animation-delay: var(--d, 0s);
        }
        @keyframes heroFadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .hero-content h1 { color:#fff; }
        .hero-content p { color:#efe9dd; max-width: 560px; }
        .hero-actions { display:flex; gap:14px; flex-wrap:wrap; margin-top: 10px; }
        .scroll-cue{
            position:absolute;left:36px;bottom:32px;z-index:3;
            display:flex;flex-direction:column;align-items:center;gap:9px;
            text-decoration:none;color:rgba(255,255,255,.8);
        }
        .scroll-cue-mouse{
            width:24px;height:38px;border:2px solid rgba(255,255,255,.5);border-radius:13px;
            display:flex;justify-content:center;padding-top:6px;
        }
        .scroll-cue-mouse span{width:4px;height:8px;border-radius:2px;background:var(--amber);animation:scrollDot 1.8s ease-in-out infinite;}
        @keyframes scrollDot{0%{opacity:1;transform:translateY(0);}70%{opacity:0;transform:translateY(13px);}100%{opacity:0;transform:translateY(0);}}
        .scroll-cue-label{font-size:.68rem;letter-spacing:.13em;text-transform:uppercase;font-weight:600;writing-mode:vertical-rl;}
        .scroll-cue:hover{color:#fff;}
        .btn {
            display:inline-flex;
            align-items:center;
            justify-content:center;
            padding: 13px 24px;
            border-radius: 999px;
            font-weight: 700;
            border: 1px solid transparent;
        }
        .btn-primary { background: var(--amber); color: #fff; box-shadow: 0 10px 22px rgba(224, 142, 79, .28); }
        .btn-primary:hover { background: #d47f3c; }
        .btn-ghost { border-color: rgba(255,255,255,.42); color:#fff; background: rgba(255,255,255,.08); }
        .btn-ghost:hover { background: rgba(255,255,255,.16); }
        .eyebrow, .pill {
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 8px 14px;
            border-radius: 999px;
            border: 1px solid rgba(224, 142, 79, 0.22);
            background: rgba(255,255,255,.7);
            color: var(--amber);
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .hero h1 { font-size: clamp(2.4rem, 6vw, 4.8rem); margin: 16px 0 12px; }
        .hero p { max-width: 720px; }
        .section { margin-top: 34px; }
        .section h2 { margin: 0 0 18px; font-size: clamp(1.6rem, 3vw, 2.3rem); }
        .grid { display:grid; gap:18px; }
        .section-head { max-width: 560px; margin-bottom: 34px; }
        .section-head h2 { margin-bottom: 12px; }
        .cards-4 { grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
        .cards-3 { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }
        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 20px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(8px);
        }
        .card h3 { margin-top: 0; margin-bottom: 8px; }
        .thumb { width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: 16px; margin-bottom: 14px; }
        .sobre-grid { display:grid; grid-template-columns: 1fr 1fr; gap: 56px; align-items:center; }
        .sobre-img { overflow:hidden; }
        .sobre-img img { width:100%; height: 420px; object-fit:cover; border-radius: 18px; }
        .video-card {
            width: min(100%, var(--video-width, 560px));
            aspect-ratio: var(--video-width, 560) / var(--video-height, 315);
            margin-top: 18px;
            border-radius: 18px;
            overflow: hidden;
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
            background: #000;
        }
        .video-card iframe {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
        }
        .horizon {
            height:3px;
            width:100%;
            background:linear-gradient(90deg,var(--pine) 0%,var(--sage) 35%,var(--amber) 100%);
            border-radius:0;
            margin-top: 22px;
        }
        .horizon {
            height: 3px;
            width: 100%;
            background: linear-gradient(90deg, var(--pine) 0%, var(--sage) 35%, var(--amber) 100%);
            border-radius: 0;
            margin-top: 22px;
        }
        .stats-row { display:flex; gap:32px; margin-top:28px; flex-wrap:wrap; }
        .stat strong { display:block; font-family:'Fraunces', serif; font-size:1.9rem; color:var(--pine); }
        .stat span { font-size:.82rem; color:var(--ink-soft); }
        .steps { display:grid; grid-template-columns: repeat(4, 1fr); gap: 28px; }
        .step { background: rgba(255,255,255,.82); border:1px solid var(--line); border-radius:22px; padding: 28px 22px; box-shadow: var(--shadow); }
        .step .num { font-family:'Fraunces', serif; font-size:1.6rem; color:var(--amber); font-weight:600; }
        .step h3 { margin: 10px 0 8px; }
        .team-grid { display:grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
        .team-card { background: rgba(255,255,255,.82); border:1px solid var(--line); border-radius:22px; overflow:hidden; box-shadow: var(--shadow); }
        .team-photo { width:100%; height:190px; background: var(--bg-soft); }
        .team-photo img { width:100%; height:100%; object-fit:cover; }
        .team-info { padding:18px 16px 20px; }
        .team-info .role, .post-tag { display:inline-block; font-size:.72rem; font-weight:800; letter-spacing:.08em; text-transform:uppercase; color:var(--sage); margin-bottom:8px; }
        .gallery-filters { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:32px; }
        .filter-btn { padding:9px 18px; border-radius:999px; border:1px solid var(--line); background: rgba(255,255,255,.78); color:var(--ink-soft); font-size:.86rem; font-weight:700; }
        .filter-btn.active { background: var(--pine); border-color: var(--pine); color:#fff; }
        .gallery-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; }
        .g-item, .post-card, .palette-card { background: rgba(255,255,255,.82); border:1px solid var(--line); border-radius:22px; box-shadow: var(--shadow); }
        .g-item { min-height: 180px; padding: 18px; }
        .blog-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:28px; }
        .post-card { overflow:hidden; }
        .post-img { height: 190px; }
        .post-img img { width:100%; height:100%; object-fit:cover; }
        .post-body { padding: 24px; }
        footer {
            background: linear-gradient(90deg, var(--pine), #16291f);
            padding: 48px 0 26px;
            margin-top: 40px;
            border-top: 1px solid rgba(255,255,255,.08);
            color: #B9C7BE;
        }
        .foot-grid {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 32px;
            margin-bottom: 32px;
        }
        .foot-grid h4 {
            color: #fff;
            font-family: 'Inter', sans-serif;
            font-size: .98rem;
            font-weight: 700;
            margin: 0 0 14px;
        }
        .foot-grid ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .foot-grid li + li {
            margin-top: 10px;
        }
        .foot-grid a {
            color: #d7e1db;
        }
        .foot-grid a:hover {
            color: #fff;
            text-decoration: underline;
        }
        .foot-bottom {
            border-top: 1px solid rgba(255,255,255,.08);
            padding-top: 20px;
            font-size: .82rem;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }
        .footer-brand {
            color: #fff;
            font-family: 'Fraunces', serif;
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: -0.01em;
        }
        .footer-brand span {
            color: var(--amber);
        }
        .toggle { display:flex; gap: 10px; align-items: center; padding: 10px 14px; border-radius: 999px; background: rgba(30, 61, 54, .06); flex-wrap: wrap; }
        .carousel {
            position: relative;
            border-radius: 28px;
            overflow: hidden;
            border: 1px solid var(--line);
            background: var(--surface-strong);
            box-shadow: 0 24px 70px rgba(30, 61, 54, .12);
        }
        .carousel-track {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: 100%;
            transition: transform .45s ease;
        }
        .slide {
            min-height: 420px;
            display: grid;
            grid-template-columns: 1.1fr .9fr;
            gap: 0;
        }
        .slide-copy, .slide-media { padding: 32px; }
        .slide-copy { display:flex; flex-direction:column; justify-content:center; gap: 14px; }
        .slide-media { display:flex; align-items:center; justify-content:center; background: linear-gradient(180deg, rgba(241,236,226,.65), rgba(250,247,242,.88)); }
        .slide-media img { width: 100%; height: 100%; object-fit: cover; border-radius: 22px; }
        .carousel-nav { position:absolute; inset: auto 18px 18px auto; display:flex; gap:10px; z-index:2; }
        .carousel-btn {
            width: 42px;
            height: 42px;
            border: 0;
            border-radius: 999px;
            color: #fff;
            background: rgba(30, 61, 54, .82);
            cursor: pointer;
        }
        .palette-strip {
            display:flex;
            gap: 12px;
            overflow-x: auto;
            padding-bottom: 4px;
        }
        .clinic-grid {
            display:grid;
            grid-template-columns: 1.3fr .9fr;
            gap: 24px;
            align-items: stretch;
        }
        .clinic-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 20px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(8px);
        }
        .clinic-map {
            position: relative;
            min-height: 420px;
            overflow: hidden;
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(241,236,226,.55), rgba(255,255,255,.9));
        }
        .clinic-map iframe {
            width: 100%;
            height: 100%;
            min-height: 420px;
            border: 0;
            display: block;
        }
        .clinic-map-empty {
            height: 100%;
            min-height: 420px;
            display:flex;
            flex-direction:column;
            justify-content:center;
            align-items:flex-start;
            padding: 28px;
            color: var(--ink-soft);
        }
        .clinic-map-empty strong {
            color: var(--pine);
            font-size: 1.2rem;
            margin-bottom: 8px;
        }
        .clinic-details {
            display:flex;
            flex-direction:column;
            gap: 16px;
            justify-content: center;
        }
        .clinic-details h3 {
            margin: 0;
            font-size: 1.8rem;
        }
        .clinic-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display:grid;
            gap: 10px;
        }
        .contact-wrap {
            display: grid;
            gap: 26px;
        }
        .contact-hero {
            max-width: 560px;
        }
        .contact-kicker {
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 8px 14px;
            border-radius: 999px;
            border: 1px solid rgba(224, 142, 79, 0.22);
            background: rgba(255,255,255,.7);
            color: var(--amber);
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }
        .contact-hero h2 {
            margin: 16px 0 12px;
            font-size: clamp(2rem, 4vw, 3.2rem);
        }
        .contact-grid-v2 {
            display: grid;
            grid-template-columns: 1.25fr 0.75fr;
            gap: 22px;
            align-items: stretch;
        }
        .contact-form-card,
        .contact-info-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid var(--line);
            border-radius: 28px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(8px);
        }
        .contact-form-card {
            padding: 28px;
        }
        .contact-form-header h3 {
            margin: 0 0 8px;
            font-size: clamp(1.4rem, 2.5vw, 2rem);
        }
        .contact-form-header p {
            margin: 0;
            color: var(--ink-soft);
        }
        .contact-success {
            margin: 16px 0 0;
            padding: 14px 16px;
            border-radius: 16px;
            background: rgba(37, 211, 102, 0.12);
            border: 1px solid rgba(37, 211, 102, 0.22);
            color: #166534;
            font-weight: 600;
        }
        .contact-form {
            display: grid;
            gap: 16px;
            margin-top: 20px;
        }
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .contact-field {
            display: grid;
            gap: 8px;
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--pine);
        }
        .contact-field input,
        .contact-field textarea {
            width: 100%;
            border-radius: 16px;
            border: 1px solid var(--line);
            background: rgba(255,255,255,.85);
            padding: 14px 16px;
            color: var(--ink);
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .contact-field input:focus,
        .contact-field textarea:focus {
            border-color: rgba(224, 142, 79, .55);
            box-shadow: 0 0 0 4px rgba(224, 142, 79, .12);
        }
        .contact-field small {
            color: #b91c1c;
            font-weight: 500;
        }
        .contact-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }
        .contact-info-card {
            padding: 22px;
            display: grid;
            gap: 14px;
            align-content: start;
        }
        .contact-info-box {
            padding: 18px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255,255,255,.92), rgba(250,247,242,.88));
            border: 1px solid var(--line);
        }
        .contact-info-title {
            display:inline-flex;
            align-items:center;
            gap:8px;
            padding: 8px 14px;
            border-radius: 999px;
            border: 1px solid rgba(107, 142, 120, 0.18);
            background: rgba(255,255,255,.7);
            color: var(--sage);
            font-size: .8rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .contact-info-box strong {
            display: block;
            color: var(--pine);
            line-height: 1.6;
            font-size: 0.98rem;
        }
        .contact-info-button {
            width: 100%;
            justify-content: center;
            margin-top: 4px;
        }
        .palette-card {
            min-width: 240px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 18px;
            padding: 14px;
        }
        .whatsapp-float {
            position: fixed;
            right: 20px;
            bottom: 20px;
            z-index: 60;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border-radius: 999px;
            background: linear-gradient(180deg, #25d366, #128c7e);
            color: #fff;
            font-weight: 800;
            box-shadow: 0 18px 36px rgba(18, 140, 126, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .whatsapp-float svg {
            width: 22px;
            height: 22px;
            flex: 0 0 auto;
        }
        .palette-samples {
            display:grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 12px;
        }
        .sample { height: 34px; border-radius: 10px; }
        @media (max-width: 768px) {
            .slide { grid-template-columns: 1fr; min-height: auto; }
            .slide-media { order: -1; min-height: 220px; }
            .slide-copy, .slide-media { padding: 20px; }
            .brand-logo {
                height: 62px;
                max-width: 170px;
            }
            .hero-cover { min-height: 72vh; }
            .hero-content { min-height: 72vh; padding: 72px 20px 44px; }
            .sobre-grid, .steps, .team-grid, .gallery-grid, .blog-grid { grid-template-columns: 1fr; }
            .clinic-grid { grid-template-columns: 1fr; }
            .contact-grid-v2, .contact-grid { grid-template-columns: 1fr; }
            .clinic-map, .clinic-map iframe, .clinic-map-empty { min-height: 300px; }
            .sobre-img img { height: 300px; }
            .video-card { width: 100%; }
            .foot-bottom { flex-direction: column; }
            .whatsapp-float {
                right: 14px;
                bottom: 14px;
                padding: 13px 14px;
            }
            .arrow { display:none; }
            .scroll-cue { display:none; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="wrap">
            <a class="brand" href="{{ url('/') }}">
                <img class="brand-logo" src="{{ $siteLogoUrl ?? asset('logo.png') }}" alt="Logo CERAPE">
                <span>{{ $settings?->brand_name ?? 'CERAPE' }}</span>
            </a>
            <nav>
                <a href="{{ url('/') }}">{{ $settings?->menu_label_home ?? 'Iniciar' }}</a>
                <a href="#sobre">{{ $settings?->menu_label_about ?? 'Quem somos' }}</a>
                <a href="#pilares">{{ $settings?->menu_label_pillars ?? 'Pilares' }}</a>
                <a href="#equipe">{{ $settings?->menu_label_team ?? 'Equipe' }}</a>
                <a href="{{ route('gallery.index') }}">{{ $settings?->menu_label_gallery ?? 'Galeria' }}</a>
                <a href="#blog">{{ $settings?->menu_label_blog ?? 'Blog' }}</a>
                <a href="#contato">{{ $settings?->menu_label_contact ?? 'Contato' }}</a>
            </nav>
        </div>
    </header>

    <main class="wrap">
        @yield('content')
    </main>

    @if ($whatsappUrl)
        <a class="whatsapp-float" href="{{ $whatsappUrl }}" target="_blank" rel="noopener" aria-label="Falar no WhatsApp">
            <svg viewBox="0 0 32 32" aria-hidden="true">
                <path d="M19.11 17.29c-.29-.15-1.72-.85-1.99-.94-.27-.1-.47-.15-.67.15-.2.29-.77.94-.94 1.13-.17.2-.35.22-.64.07-.29-.15-1.23-.45-2.35-1.43-.87-.78-1.46-1.74-1.63-2.03-.17-.29-.02-.45.13-.59.13-.13.29-.35.44-.52.15-.17.2-.29.29-.49.1-.2.05-.37-.02-.52-.07-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.51l-.57-.01c-.2 0-.52.07-.79.37-.27.29-1.04 1.02-1.04 2.48 0 1.46 1.07 2.87 1.22 3.07.15.2 2.1 3.2 5.09 4.49.71.31 1.26.49 1.69.63.71.23 1.36.2 1.87.12.57-.09 1.72-.7 1.96-1.37.24-.67.24-1.24.17-1.37-.07-.13-.27-.2-.56-.35Zm-3.11 8.38h-.01a10.97 10.97 0 0 1-5.61-1.54l-.4-.24-4.18 1.1 1.12-4.07-.26-.42a10.88 10.88 0 0 1-1.67-5.79c0-6.02 4.9-10.92 10.93-10.92 2.92 0 5.66 1.14 7.71 3.2a10.84 10.84 0 0 1 3.2 7.72c0 6.02-4.9 10.96-10.83 10.96Zm9.28-20.23A13.04 13.04 0 0 0 16 0C7.18 0 .02 7.16.02 16c0 2.82.74 5.57 2.14 7.99L0 32l8.18-2.14A15.96 15.96 0 0 0 16 31.98h.01c8.82 0 15.97-7.16 15.97-15.98a15.92 15.92 0 0 0-4.7-11.56Z" fill="currentColor"/>
            </svg>
            <span>WhatsApp</span>
        </a>
    @endif

    <script>
        (() => {
            const slides = [...document.querySelectorAll('.hero-carousel .slide')];
            const revealItems = [...document.querySelectorAll('.reveal')];
            if ('IntersectionObserver' in window) {
                const revealObserver = new IntersectionObserver((entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('in-view');
                            revealObserver.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });

                revealItems.forEach((item) => revealObserver.observe(item));
            } else {
                revealItems.forEach((item) => item.classList.add('in-view'));
            }

            if (!slides.length) return;
            let index = Math.max(0, slides.findIndex((slide) => slide.classList.contains('active')));
            if (index < 0) index = 0;

            const go = (next) => {
                index = (next + slides.length) % slides.length;
                slides.forEach((slide, i) => slide.classList.toggle('active', i === index));
            };

            window.moveSlide = (direction) => go(index + direction);
            if (slides.length > 1) {
                setInterval(() => go(index + 1), 7000);
            }
        })();

    </script>

    <footer>
        <div class="wrap">
            <div class="foot-grid">
                <div>
                    <a href="{{ url('/') }}" class="footer-brand">{{ $settings?->brand_name ?? 'CER' }}<span>APE</span></a>
                    <p style="max-width:280px;font-size:.88rem;margin-top:10px;">{{ $settings?->contact_section_description ?? 'Acolhimento e tratamento para uma nova etapa de vida.' }}</p>
                </div>
                <div>
                    <h4>Navegação</h4>
                    <ul>
                        <li><a href="#sobre">{{ $settings?->menu_label_about ?? 'Sobre Nós' }}</a></li>
                        <li><a href="#jornada">{{ $settings?->menu_label_pillars ?? 'A Jornada' }}</a></li>
                        <li><a href="{{ route('gallery.index') }}">{{ $settings?->menu_label_gallery ?? 'Galeria' }}</a></li>
                        <li><a href="#blog">{{ $settings?->menu_label_blog ?? 'Blog' }}</a></li>
                        <li><a href="#contato">{{ $settings?->menu_label_contact ?? 'Contato' }}</a></li>
                    </ul>
                </div>
                <div>
                    <h4>{{ $settings?->contact_section_title ?? 'Contato' }}</h4>
                    <ul>
                        <li>
                            @if ($whatsappUrl)
                                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener">{{ $settings?->contact_whatsapp_cta_label ?? 'WhatsApp' }}: {{ $settings?->whatsapp_number }}</a>
                            @else
                                {{ $settings?->contact_section_description ?? 'Configurar no /frontend' }}
                            @endif
                        </li>
                        <li>{{ $settings?->contact_email_line ?? $settings?->contact_email ?? 'contato@cerape.com.br' }}</li>
                    </ul>
                </div>
            </div>
            <div class="foot-bottom">
                <span>© 2026 {{ $settings?->brand_name ?? 'CERAPE' }}. Todos os direitos reservados.</span>
                <span>{{ $settings?->contact_whatsapp_footer ?? 'Atendimento confidencial e humanizado.' }}</span>
            </div>
        </div>
    </footer>
</body>
</html>

@php
    $isFamily = \App\Support\PortalContext::isFamilyUser();
    $familyTheme = \App\Support\PortalContext::familyTheme();
@endphp

@if ($isFamily)
    <style>
        :root {
            --family-primary: {{ $familyTheme['primary'] }};
            --family-secondary: {{ $familyTheme['secondary'] }};
            --family-accent: {{ $familyTheme['accent'] }};
            --family-surface: {{ $familyTheme['surface'] }};
            --family-surface-strong: {{ $familyTheme['surfaceStrong'] }};
            --family-ink: {{ $familyTheme['ink'] }};
        }

        .fi-sidebar {
            background:
                radial-gradient(circle at top, color-mix(in srgb, var(--family-accent) 28%, transparent), transparent 28rem),
                linear-gradient(180deg, color-mix(in srgb, var(--family-surface-strong) 72%, white), rgba(255, 255, 255, 0.97));
        }

        .dark .fi-sidebar {
            background:
                radial-gradient(circle at top, color-mix(in srgb, var(--family-accent) 20%, transparent), transparent 26rem),
                linear-gradient(180deg, rgba(17, 24, 39, 0.98), rgba(3, 7, 18, 0.98));
        }

        .fi-topbar {
            backdrop-filter: blur(18px);
            background:
                linear-gradient(90deg, color-mix(in srgb, var(--family-surface) 88%, white), color-mix(in srgb, var(--family-surface-strong) 80%, white));
        }

        .fi-main-ctn {
            background:
                radial-gradient(circle at top right, color-mix(in srgb, var(--family-primary) 10%, transparent), transparent 24rem),
                radial-gradient(circle at left top, color-mix(in srgb, var(--family-secondary) 10%, transparent), transparent 22rem);
        }

        .dark .fi-main-ctn {
            background:
                radial-gradient(circle at top right, color-mix(in srgb, var(--family-primary) 6%, transparent), transparent 24rem),
                radial-gradient(circle at left top, color-mix(in srgb, var(--family-secondary) 8%, transparent), transparent 22rem);
        }

        .family-carousel .carousel-item {
            display: block;
        }

        .family-gallery-shell {
            background-image:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.75), transparent 26rem),
                linear-gradient(180deg, var(--gallery-panel, rgba(239, 244, 247, 0.98)), color-mix(in srgb, var(--gallery-panel, #eef2f5) 82%, white));
        }

        .dark .family-gallery-shell {
            background-image:
                radial-gradient(circle at top, rgba(255, 255, 255, 0.06), transparent 26rem),
                linear-gradient(180deg, var(--gallery-panel-dark, rgba(15, 23, 42, 0.78)), rgba(15, 23, 42, 0.92));
        }

        .family-gallery-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .family-gallery-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
        }

        .family-carousel .carousel-control-prev-icon,
        .family-carousel .carousel-control-next-icon {
            font-family: Georgia, serif;
            font-weight: 700;
        }

        .family-carousel .carousel-indicators button {
            border: 0;
            padding: 0;
        }

        .family-gallery-card button:hover {
            background: color-mix(in srgb, var(--gallery-accent, #84cc16) 10%, white);
        }

        .family-theme-chip {
            background: linear-gradient(135deg, color-mix(in srgb, var(--family-primary) 18%, white), color-mix(in srgb, var(--family-secondary) 14%, white));
            color: var(--family-ink);
        }
    </style>
@endif

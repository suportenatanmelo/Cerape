@php
    $user = auth()->user();
    $isFamily = \App\Support\PortalContext::isFamilyUser($user);
    $theme = \App\Support\PortalContext::familyTheme();
    $celebration = \App\Support\PortalContext::brazilianCelebration();
@endphp

@if ($isFamily)
    <div class="mb-6 rounded-[1.6rem] border p-4 shadow-sm" style="border-color: color-mix(in srgb, {{ $theme['primary'] }} 18%, white); background: linear-gradient(160deg, {{ $theme['surface'] }}, color-mix(in srgb, {{ $theme['surfaceStrong'] }} 70%, white));">
        <div class="text-[11px] font-semibold uppercase tracking-[0.24em]" style="color: {{ $theme['secondary'] }};">
            Ambiente da familia
        </div>
        <div class="mt-2 text-sm font-semibold" style="color: {{ $theme['ink'] }};">
            {{ $theme['name'] }}
        </div>
        <div class="mt-2 text-sm leading-6" style="color: color-mix(in srgb, {{ $theme['ink'] }} 72%, white);">
            {{ $celebration['title'] ?? 'Cada acesso traz uma nova paleta para deixar a experiencia mais leve, humana e acolhedora.' }}
        </div>
    </div>
@endif

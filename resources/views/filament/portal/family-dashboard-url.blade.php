@php
    $prettyUrl = \App\Support\PortalContext::familyDashboardUrl();
    $isFamily = \App\Support\PortalContext::isFamilyUser();
    $isDashboardPath = request()->path() === 'admin';
@endphp

@if ($isFamily && filled($prettyUrl) && $isDashboardPath)
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            if (window.location.pathname === '/admin') {
                window.history.replaceState({}, document.title, @js($prettyUrl));
            }
        });
    </script>
@endif

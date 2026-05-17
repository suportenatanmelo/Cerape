<?php
    $prettyUrl = \App\Support\PortalContext::familyDashboardUrl();
    $isFamily = \App\Support\PortalContext::isFamilyUser();
    $isDashboardPath = request()->path() === 'admin';
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isFamily && filled($prettyUrl) && $isDashboardPath): ?>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            if (window.location.pathname === '/admin') {
                window.history.replaceState({}, document.title, <?php echo \Illuminate\Support\Js::from($prettyUrl)->toHtml() ?>);
            }
        });
    </script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\cerape\resources\views\filament\portal\family-dashboard-url.blade.php ENDPATH**/ ?>
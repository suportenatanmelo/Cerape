<?php
    $isFamily = \App\Support\PortalContext::isFamilyUser();
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isFamily): ?>
    <style>
        .fi-sidebar {
            background:
                radial-gradient(circle at top, rgba(251, 207, 232, 0.28), transparent 28rem),
                linear-gradient(180deg, rgba(255, 251, 235, 0.9), rgba(255, 255, 255, 0.96));
        }

        .dark .fi-sidebar {
            background:
                radial-gradient(circle at top, rgba(244, 114, 182, 0.16), transparent 26rem),
                linear-gradient(180deg, rgba(17, 24, 39, 0.98), rgba(3, 7, 18, 0.98));
        }

        .fi-topbar {
            backdrop-filter: blur(18px);
        }

        .fi-main-ctn {
            background:
                radial-gradient(circle at top right, rgba(251, 191, 36, 0.08), transparent 24rem),
                radial-gradient(circle at left top, rgba(244, 114, 182, 0.09), transparent 22rem);
        }

        .dark .fi-main-ctn {
            background:
                radial-gradient(circle at top right, rgba(251, 191, 36, 0.04), transparent 24rem),
                radial-gradient(circle at left top, rgba(244, 114, 182, 0.06), transparent 22rem);
        }
    </style>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\cerape\resources\views/filament/portal/head-theme.blade.php ENDPATH**/ ?>
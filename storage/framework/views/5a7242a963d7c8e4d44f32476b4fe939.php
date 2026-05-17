<?php
    $user = auth()->user();
    $isFamily = \App\Support\PortalContext::isFamilyUser($user);
    $theme = \App\Support\PortalContext::familyTheme();
    $celebration = \App\Support\PortalContext::brazilianCelebration();
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isFamily): ?>
    <div class="mb-6 rounded-[1.6rem] border p-4 shadow-sm" style="border-color: color-mix(in srgb, <?php echo e($theme['primary']); ?> 18%, white); background: linear-gradient(160deg, <?php echo e($theme['surface']); ?>, color-mix(in srgb, <?php echo e($theme['surfaceStrong']); ?> 70%, white));">
        <div class="text-[11px] font-semibold uppercase tracking-[0.24em]" style="color: <?php echo e($theme['secondary']); ?>;">
            Ambiente da familia
        </div>
        <div class="mt-2 text-sm font-semibold" style="color: <?php echo e($theme['ink']); ?>;">
            <?php echo e($theme['name']); ?>

        </div>
        <div class="mt-2 text-sm leading-6" style="color: color-mix(in srgb, <?php echo e($theme['ink']); ?> 72%, white);">
            <?php echo e($celebration['title'] ?? 'Cada acesso traz uma nova paleta para deixar a experiencia mais leve, humana e acolhedora.'); ?>

        </div>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\cerape\resources\views/filament/portal/sidebar-identity.blade.php ENDPATH**/ ?>
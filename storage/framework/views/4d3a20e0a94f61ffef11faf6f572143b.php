<?php
    $user = auth()->user();
    $isFamily = \App\Support\PortalContext::isFamilyUser($user);
    $acolhido = $isFamily ? $user?->acolhido : null;
    $familyTheme = \App\Support\PortalContext::familyTheme();
    $familyBanner = \App\Support\PortalContext::familyBannerCopy($user);
    $celebration = \App\Support\PortalContext::brazilianCelebration();
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user && $isFamily): ?>
    <section
        class="mb-6 overflow-hidden rounded-[2rem] border shadow-sm ring-1 ring-white/70"
        style="border-color: color-mix(in srgb, <?php echo e($familyTheme['primary']); ?> 25%, white); background: linear-gradient(135deg, <?php echo e($familyTheme['surface']); ?>, color-mix(in srgb, <?php echo e($familyTheme['surfaceStrong']); ?> 74%, white), color-mix(in srgb, <?php echo e($familyTheme['accent']); ?> 10%, white));"
    >
        <div class="grid gap-6 px-6 py-6 lg:grid-cols-[minmax(0,1fr)_280px] lg:px-8">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.28em]" style="color: <?php echo e($familyTheme['secondary']); ?>;">
                    Portal da Familia
                </p>
                <h1 class="mt-2 text-2xl font-semibold tracking-tight" style="color: <?php echo e($familyTheme['ink']); ?>;">
                    <?php echo e($familyBanner['title']); ?>

                </h1>
                <p class="mt-3 max-w-2xl text-sm leading-7" style="color: color-mix(in srgb, <?php echo e($familyTheme['ink']); ?> 72%, white);">
                    <?php echo e($familyBanner['subtitle']); ?>

                </p>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($celebration): ?>
                    <div class="mt-4 inline-flex rounded-full px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.24em]" style="background-color: color-mix(in srgb, <?php echo e($familyTheme['accent']); ?> 14%, white); color: <?php echo e($familyTheme['ink']); ?>;">
                        <?php echo e($celebration['badge']); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="rounded-[1.5rem] p-4 shadow-sm backdrop-blur" style="background-color: rgba(255,255,255,0.75); border: 1px solid color-mix(in srgb, <?php echo e($familyTheme['primary']); ?> 15%, white);">
                <p class="text-[11px] font-medium uppercase tracking-[0.2em]" style="color: <?php echo e($familyTheme['secondary']); ?>;">
                    Consulta atual
                </p>
                <p class="mt-2 text-lg font-semibold" style="color: <?php echo e($familyTheme['ink']); ?>;">
                    <?php echo e($acolhido?->nome_completo_paciente ?? 'Acolhido vinculado'); ?>

                </p>
                <p class="mt-1 text-sm" style="color: color-mix(in srgb, <?php echo e($familyTheme['ink']); ?> 70%, white);">
                    Informacoes do acolhido disponiveis no menu lateral.
                </p>
                <p class="mt-3 text-xs uppercase tracking-[0.18em]" style="color: <?php echo e($familyTheme['primary']); ?>;">
                    Tema de hoje: <?php echo e($familyTheme['name']); ?>

                </p>
            </div>
        </div>
    </section>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\cerape\resources\views\filament\portal\page-banner.blade.php ENDPATH**/ ?>
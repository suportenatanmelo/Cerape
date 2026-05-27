<?php
    $urls = $getImageUrls();
    $limit = $getLimit();
    $visibleUrls = $limit ? array_slice($urls, 0, $limit) : $urls;
    $remaining = $limit ? max(0, count($urls) - $limit) : 0;
    $width = $getThumbWidth() ?? 40;
    $height = $isSquare() && $width ? $width : $getThumbHeight() ?? 40;
    $isStacked = $isStacked();
    $stackedOverlap = $getStackedOverlap();
    $isSquare = $isSquare();
    $isCircular = $isCircular();
    $ringWidth = $getRingWidth();
    $ringColor = $getRingColor() ?? 'white';
    $galleryId = 'gallery-col-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());

    // Determine border radius
    $borderRadius = $isCircular ? '9999px' : ($isSquare ? '0.5rem' : '0.25rem');

    // Ring/border style using box-shadow for better stacking appearance
    $ringStyle = $ringWidth > 0 ? "box-shadow: 0 0 0 {$ringWidth}px {$ringColor};" : '';

    // Calculate stacked margin - negative margin for overlap effect
    $stackedMarginPx = $stackedOverlap * 4; // 4px per overlap unit
?>

<div id="<?php echo e($galleryId); ?>"
    style="display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; align-items: center;"
    data-viewer-gallery wire:ignore.self>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $visibleUrls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $src): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
        <img src="<?php echo e($src); ?>" loading="lazy" class="object-cover object-center shrink-0"
            style="
                width: <?php echo e($width); ?>px;
                height: <?php echo e($height); ?>px;
                border-radius: <?php echo e($borderRadius); ?>;
                <?php echo e($ringStyle); ?>

                position: relative;
                z-index: <?php echo e(count($visibleUrls) - $index); ?>;
                <?php if($isStacked && $index > 0): ?> margin-inline-start: -<?php echo e($stackedMarginPx); ?>px; <?php endif; ?>
                cursor: pointer;
                transition: transform 0.15s ease-in-out;
            "
            onmouseover="this.style.transform='scale(1.1)'; this.style.zIndex='<?php echo e(count($visibleUrls) + 10); ?>';"
            onmouseout="this.style.transform='scale(1)'; this.style.zIndex='<?php echo e(count($visibleUrls) - $index); ?>';"
            alt="image" />
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($remaining > 0 && $shouldShowRemainingText()): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shouldShowRemainingTextBadge()): ?>
            
            <div
                style="position: relative; margin-inline-start: -0.5rem; align-self: flex-start; margin-top: -0.3rem; z-index: 99;">
                <?php if (isset($component)) { $__componentOriginal986dce9114ddce94a270ab00ce6c273d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal986dce9114ddce94a270ab00ce6c273d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.badge','data' => ['size' => 'sm','color' => 'primary','class' => '!rounded-full !aspect-square !p-0 !min-w-6 !h-6 !justify-center','style' => 'height: 26px; border-radius: 50%;']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['size' => 'sm','color' => 'primary','class' => '!rounded-full !aspect-square !p-0 !min-w-6 !h-6 !justify-center','style' => 'height: 26px; border-radius: 50%;']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    +<?php echo e($remaining); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal986dce9114ddce94a270ab00ce6c273d)): ?>
<?php $attributes = $__attributesOriginal986dce9114ddce94a270ab00ce6c273d; ?>
<?php unset($__attributesOriginal986dce9114ddce94a270ab00ce6c273d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal986dce9114ddce94a270ab00ce6c273d)): ?>
<?php $component = $__componentOriginal986dce9114ddce94a270ab00ce6c273d; ?>
<?php unset($__componentOriginal986dce9114ddce94a270ab00ce6c273d); ?>
<?php endif; ?>
            </div>
        <?php else: ?>
            
            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 shrink-0"
                style="margin-inline-start: 0.25rem;">
                +<?php echo e($remaining); ?>

            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>


<?php /**PATH C:\laragon\www\cerape\vendor\al-saloul\filament-image-gallery\resources\views/columns/image-gallery.blade.php ENDPATH**/ ?>
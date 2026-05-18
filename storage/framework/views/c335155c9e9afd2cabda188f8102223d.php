<?php
    $state = $getState();

    if ($state instanceof \Illuminate\Support\Collection) {
        $state = $state->all();
    }

    $state = \Illuminate\Support\Arr::wrap($state);

    $limit = $getLimit();
    $limitedState = $limit ? array_slice($state, 0, $limit) : $state;
    $remaining = $limit ? max(0, count($state) - $limit) : 0;

    $isCircular = $isCircular();
    $isSquare = $isSquare();
    $isStacked = $isStacked();
    $overlap = $isStacked ? $getOverlap() ?? 2 : 0;

    $defaultWidth = $getWidth();
    $defaultHeight = $getHeight();

    $defaultWidth = $defaultWidth ? (is_numeric($defaultWidth) ? $defaultWidth . 'px' : $defaultWidth) : 'auto';
    $defaultHeight = $defaultHeight ? (is_numeric($defaultHeight) ? $defaultHeight . 'px' : $defaultHeight) : '150px';

    $galleryId = 'gallery-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());

    // Calculate margin for stacked images using inline styles (Tailwind-safe)
    $stackedMarginValue = $overlap * 0.25;
    $stackedMargin = $isStacked && $overlap > 0 ? "-{$stackedMarginValue}rem" : '0';
?>

<?php if (isset($component)) { $__componentOriginal511d4862ff04963c3c16115c05a86a9d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal511d4862ff04963c3c16115c05a86a9d = $attributes; } ?>
<?php $component = Illuminate\View\DynamicComponent::resolve(['component' => $getEntryWrapperView()] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('dynamic-component'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\DynamicComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['entry' => $entry]); ?>
    <div id="<?php echo e($galleryId); ?>"
        <?php echo e($attributes->merge($getExtraAttributes(), escape: false)->class(['fi-in-image', 'flex items-center', 'gap-1.5' => !$isStacked])); ?>

        style="display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; overflow-x: auto; max-width: 100%; scrollbar-width: thin; cursor: pointer;"
        data-viewer-gallery wire:ignore.self>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $limitedState; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $stateItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <img src="<?php echo e($getImageUrl($stateItem)); ?>"
                style="
                    height: <?php echo e($defaultHeight); ?>;
                    width: <?php echo e($defaultWidth); ?>;
                    <?php if($isStacked && $index > 0): ?> margin-inline-start: <?php echo e($stackedMargin); ?>; <?php endif; ?>
                "
                <?php echo e($getExtraImgAttributeBag()->class([
                    'max-w-none object-cover object-center',
                    'rounded-full' => $isCircular,
                    'rounded-lg' => $isSquare,
                    'ring-white dark:ring-gray-900' => $isStacked,
                    'ring-2' => $isStacked && $overlap > 0,
                ])); ?> />
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($remaining > 0 && ($limitedRemainingText ?? true)): ?>
            <?php
                $showBadge = ($getExtraAttributes()['data-remaining-text-badge'] ?? 'false') === 'true';
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showBadge): ?>
                <div
                    style="position: relative; margin-inline-start: -1rem; align-self: flex-start; margin-top: -0.3rem; z-index: 99;">
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
                <div style="
                        min-height: <?php echo e($defaultHeight); ?>;
                        min-width: <?php echo e($defaultWidth); ?>;
                        height: <?php echo e($defaultHeight); ?>;
                        width: <?php echo e($defaultWidth); ?>;
                        <?php if($isStacked): ?> margin-inline-start: <?php echo e($stackedMargin); ?>; <?php endif; ?>
                    "
                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                        'flex items-center justify-center bg-gray-100 font-medium text-gray-500 ring-white dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-900',
                        'rounded-full' => $isCircular,
                        'rounded-lg' => $isSquare,
                        'ring-2' => $isStacked && $overlap > 0,
                    ]); ?>">
                    <span class="-ms-0.5 text-xs">
                        +<?php echo e($remaining); ?>

                    </span>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $attributes = $__attributesOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__attributesOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal511d4862ff04963c3c16115c05a86a9d)): ?>
<?php $component = $__componentOriginal511d4862ff04963c3c16115c05a86a9d; ?>
<?php unset($__componentOriginal511d4862ff04963c3c16115c05a86a9d); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\cerape\vendor\al-saloul\filament-image-gallery\resources\views\infolists\entries\image-entry-gallery.blade.php ENDPATH**/ ?>
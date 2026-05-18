<?php
    $urls = $getImageUrls();
    $width = $getThumbWidth();
    $height = $getThumbHeight();
    $gap = $getImageGap();
    $rounded = $getRounded();
    $zoomCursor = $hasZoomCursor();
    $wrapperClass = $getWrapperClass() ?? '';
    $galleryId = 'gallery-entry-' . str_replace(['{', '}', '-'], '', (string) \Illuminate\Support\Str::uuid());

    // Size styles - only add if width/height specified
    $sizeStyle = '';
    if ($width) {
        $sizeStyle .= "width: {$width}px;";
    }
    if ($height) {
        $sizeStyle .= " height: {$height}px;";
    }
    if ($width || $height) {
        $sizeStyle .= ' flex-shrink: 0;';
    }
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
    <div id="<?php echo e($galleryId); ?>" class="fi-in-image image-gallery <?php echo e($gap); ?> my-2 pb-2 select-none <?php echo e($wrapperClass); ?>"
        style="display: flex !important; flex-direction: row !important; flex-wrap: nowrap !important; overflow-x: auto; max-width: 100%; scrollbar-width: thin; cursor: pointer;"
        data-viewer-gallery>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $src): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <img src="<?php echo e($src); ?>" loading="lazy"
                class="<?php echo e($rounded); ?> shadow object-cover border border-gray-200 dark:border-gray-700 hover:scale-105 transition cursor-pointer"
                style="<?php echo e($sizeStyle); ?> flex-shrink: 0;" alt="image" />
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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


<?php /**PATH C:\laragon\www\cerape\vendor\al-saloul\filament-image-gallery\resources\views\entries\image-gallery.blade.php ENDPATH**/ ?>
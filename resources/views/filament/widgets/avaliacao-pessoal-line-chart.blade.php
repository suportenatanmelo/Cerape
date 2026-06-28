@php
    use Filament\Widgets\View\Components\ChartWidgetComponent;
    use Illuminate\View\ComponentAttributeBag;

    $color = $this->getColor();
    $heading = $this->getHeading();
    $description = $this->getDescription();
    $acolhidoOptions = $this->getAcolhidoOptions();
    $filters = [
        'semanal' => 'Semanal',
        'mensal' => 'Mensal',
        'semestral' => 'Semestral',
        'anual' => 'Anual',
    ];
    $type = $this->getType();
    $maxHeight = $this->getMaxHeight();
    $hasMaxHeight = filled($maxHeight) && $maxHeight !== '100%';
@endphp

<x-filament-widgets::widget class="fi-wi-chart">
    <div class="mx-auto w-full max-w-5xl">
        <x-filament::section
            :description="$description"
            :heading="$heading"
        >
            <x-slot name="afterHeader">
                <div class="flex flex-col gap-3 md:flex-row md:items-center">
                    <x-filament::input.wrapper class="w-full md:max-w-sm">
                        <x-filament::input.select wire:model.live="acolhidoId">
                            @foreach ($acolhidoOptions as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>

                    <x-filament::input.wrapper class="w-full md:max-w-[180px]">
                        <x-filament::input.select wire:model.live="filter">
                            @foreach ($filters as $value => $label)
                                <option value="{{ $value }}">
                                    {{ $label }}
                                </option>
                            @endforeach
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>
            </x-slot>

            <div>
                <div
                    x-load
                    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('chart', 'filament/widgets') }}"
                    wire:ignore
                    data-chart-type="{{ $type }}"
                    x-data="chart({
                                cachedData: @js($this->getCachedData()),
                                options: @js($this->getOptions()),
                                type: @js($type),
                            })"
                    {{
                        (new ComponentAttributeBag)
                            ->color(ChartWidgetComponent::class, $color)
                            ->class([
                                'fi-wi-chart-canvas-ctn',
                                'fi-wi-chart-canvas-ctn-no-aspect-ratio' => $hasMaxHeight,
                            ])
                    }}
                >
                    <canvas
                        x-ref="canvas"
                        @style([
                            'width: 100%',
                            'height: 100%; max-height: 100%' => ! $hasMaxHeight,
                            "max-height: {$maxHeight}" => $hasMaxHeight,
                        ])
                    ></canvas>

                    <span
                        x-ref="backgroundColorElement"
                        class="fi-wi-chart-bg-color"
                    ></span>

                    <span
                        x-ref="borderColorElement"
                        class="fi-wi-chart-border-color"
                    ></span>

                    <span
                        x-ref="gridColorElement"
                        class="fi-wi-chart-grid-color"
                    ></span>

                    <span
                        x-ref="textColorElement"
                        class="fi-wi-chart-text-color"
                    ></span>
                </div>
            </div>
        </x-filament::section>
    </div>
</x-filament-widgets::widget>

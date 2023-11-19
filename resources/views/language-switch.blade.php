@php
    $locales = $languageSwitch->getLocales();
    $isCircular = $languageSwitch->isCircular();
    $isFlagsOnly = $languageSwitch->isFlagsOnly();
    $hasFlags = filled($languageSwitch->getFlags());
    $isDisplayOn = $languageSwitch->isDisplayOn();
    $alignment = $languageSwitch->getPlacement()->value;
    $placement = match(true){
        $alignment === 'top-center' => 'bottom',
        $alignment === 'bottom-center' => 'top',
        default => 'bottom-end',
    };
@endphp

@if ($isDisplayOn)
    <div @class([
        'fls-display-on fixed w-full flex p-4 z-50',
        'top-0' => str_contains($alignment, 'top'),
        'bottom-0' => str_contains($alignment, 'bottom'),
        'justify-start' => str_contains($alignment, 'left'),
        'justify-end' => str_contains($alignment, 'right'),
        'justify-center' => str_contains($alignment, 'center'),
    ])>
        <div class="rounded-lg bg-gray-50 dark:bg-gray-950">
            @include('filament-language-switch::switch')
        </div>
    </div>
@else
    @include('filament-language-switch::switch')
@endif
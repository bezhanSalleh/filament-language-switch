@php
    $languageSwitch = \BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch::make();
    $locales = $languageSwitch->getLocales();
    $isCircular = $languageSwitch->isCircular();
    $isFlagsOnly = $languageSwitch->isFlagsOnly();
    $hasFlags = filled($languageSwitch->getFlags());
    $isVisibleOutsidePanels = $languageSwitch->isVisibleOutsidePanels();
    $outsidePanelsPlacement = $languageSwitch->getOutsidePanelPlacement()->value;
    $placement = match(true){
        $outsidePanelsPlacement === 'top-center' && $isFlagsOnly => 'bottom',
        $outsidePanelsPlacement === 'bottom-center' && $isFlagsOnly => 'top',
        ! $isVisibleOutsidePanels && $isFlagsOnly=> 'bottom',
        default => 'bottom-end',
    };
@endphp
<div>
    @if ($isVisibleOutsidePanels)
        <div @class([
            'fls-display-on fixed w-fit flex p-4 z-50',
            'top-0' => str_contains($outsidePanelsPlacement, 'top'),
            'bottom-0' => str_contains($outsidePanelsPlacement, 'bottom'),
            'justify-start' => str_contains($outsidePanelsPlacement, 'left'),
            'justify-end' => str_contains($outsidePanelsPlacement, 'right'),
            'justify-center' => str_contains($outsidePanelsPlacement, 'center'),
        ])>
            <div class="rounded-lg bg-gray-50 dark:bg-gray-950">
                @include('filament-language-switch::switch')
            </div>
        </div>
    @else
        @include('filament-language-switch::switch')
    @endif
</div>
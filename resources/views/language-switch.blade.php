@php
    $languageSwitch = \BezhanSalleh\LanguageSwitch\LanguageSwitch::make();
    $isVisibleOutsidePanels = $languageSwitch->isVisibleOutsidePanels();
    $outsidePanelsPlacement = $languageSwitch->getOutsidePanelPlacement()->value;
    $isFlagsOnly = $languageSwitch->isFlagsOnly();

    // RTL Support Logic
    $isRtl = in_array(app()->getLocale(), ['ar', 'fa', 'he', 'ur'], true);
    $defaultPlacement = $isRtl ? 'bottom-start' : 'bottom-end';

    $placement = match (true) {
        $outsidePanelsPlacement === 'top-center' && $isFlagsOnly => 'bottom',
        $outsidePanelsPlacement === 'bottom-center' && $isFlagsOnly => 'top',
        !$isVisibleOutsidePanels && $isFlagsOnly => 'bottom',
        default => $defaultPlacement,
    };

    $displayAs = $languageSwitch->getDisplayAs();
    $isUserMenuItem = $languageSwitch->isRenderedAsUserMenuItem() && !$isVisibleOutsidePanels;
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
                @include('language-switch::switch')
            </div>
        </div>
    @else
        @include('language-switch::switch')
    @endif
</div>

@php
    $languageSwitch = \BezhanSalleh\LanguageSwitch\LanguageSwitch::make();
    $isVisibleOutsidePanels = $languageSwitch->isVisibleOutsidePanels();
    $outsidePanelsPlacement = $languageSwitch->getOutsidePanelPlacement()->value;
    $isFlagsOnly = $languageSwitch->isFlagsOnly();

    // RTL Support Logic
    $isRtl = in_array(app()->getLocale(),['ar', 'fa', 'he', 'ur'], true);
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
            'fls-display-on fixed w-full flex py-4 z-50 pointer-events-none',
            'top-0' => str_contains($outsidePanelsPlacement, 'top'),
            'bottom-0' => str_contains($outsidePanelsPlacement, 'bottom'),
            'justify-start' => str_contains($outsidePanelsPlacement, 'left'),
            'justify-end' => str_contains($outsidePanelsPlacement, 'right'),
            'justify-center' => str_contains($outsidePanelsPlacement, 'center'),
        ])>
            <!-- Here we add inline CSS to guarantee the gap exists so they don't overlap -->
            <div class="pointer-events-auto" style="margin-inline-start: 60px;">
                @include('language-switch::switch')
            </div>
        </div>
    @else
        @include('language-switch::switch')
    @endif
</div>

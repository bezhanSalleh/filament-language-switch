@php
    $ls = \BezhanSalleh\LanguageSwitch\LanguageSwitch::make();
    $layout = $ls->getTriggerLayout();

    $locales = $ls->getLocales();
    $isCircular = $ls->isCircular();
    $isFlagsOnly = $ls->isFlagsOnly();
    $hasFlags = filled($ls->getFlags());
    $displayMode = $ls->getDisplayMode();
    $columns = $ls->getColumns();
    $maxHeight = $ls->getMaxHeight();
    $flagHeight = $ls->getFlagHeight();
    $avatarHeight = $ls->getAvatarHeight();

    $rtl = __('filament-panels::layout.direction') === 'rtl';
    $customPlacement = $ls->getDropdownPlacement();

    $placementMode = $layout->outsidePanelPlacementMode;
@endphp

<div @class(['fi-ls', 'fi-circular' => $isCircular, 'fi-flags-only' => $isFlagsOnly])>
    @if ($layout->renderContext === 'outside-panel')
        <div @class([
            'fi-ls-floating fixed z-40 flex p-4' => $placementMode === \BezhanSalleh\LanguageSwitch\Enums\PlacementMode::Fixed,
            'fi-ls-sticky sticky z-40 flex p-4' => $placementMode === \BezhanSalleh\LanguageSwitch\Enums\PlacementMode::Sticky,
            'fi-ls-inline flex p-4' => $placementMode === \BezhanSalleh\LanguageSwitch\Enums\PlacementMode::Relative,
            $layout->outsidePanelPositionClasses => $placementMode !== \BezhanSalleh\LanguageSwitch\Enums\PlacementMode::Relative,
            $layout->outsidePanelSelfAlignClass => $placementMode === \BezhanSalleh\LanguageSwitch\Enums\PlacementMode::Relative,
        ])>
            @if ($displayMode === \BezhanSalleh\LanguageSwitch\Enums\DisplayMode::Modal)
                @include('language-switch::partials.modal')
            @else
                @include('language-switch::partials.dropdown')
            @endif
        </div>
    @elseif ($displayMode === \BezhanSalleh\LanguageSwitch\Enums\DisplayMode::Modal)
        @include('language-switch::partials.modal')
    @else
        @include('language-switch::partials.dropdown')
    @endif
</div>

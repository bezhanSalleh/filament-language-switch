@php
    use BezhanSalleh\LanguageSwitch\Enums\DisplayMode;
    use BezhanSalleh\LanguageSwitch\Enums\Placement;
    use BezhanSalleh\LanguageSwitch\Enums\PlacementMode;

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

    $p = $layout->outsidePanelPlacement;
    $m = $layout->outsidePanelPlacementMode;
    $isPinned = $m === PlacementMode::Pinned;
    $isStatic = $m === PlacementMode::Static;
    $isRelative = $m === PlacementMode::Relative;
    $isInFlow = $isStatic || $isRelative;
@endphp

<div @class(['fi-ls', 'fi-circular' => $isCircular, 'fi-flags-only' => $isFlagsOnly])>
    @if ($layout->renderContext === 'outside-panel')
        <div @class([
            'flex p-4',
            'fi-ls-pinned fixed z-40' => $isPinned,
            'top-0 start-0' => $isPinned && $p === Placement::TopStart,
            'top-0 left-1/2 -translate-x-1/2' => $isPinned && $p === Placement::TopCenter,
            'top-0 end-0' => $isPinned && $p === Placement::TopEnd,
            'bottom-0 start-0' => $isPinned && $p === Placement::BottomStart,
            'bottom-0 left-1/2 -translate-x-1/2' => $isPinned && $p === Placement::BottomCenter,
            'bottom-0 end-0' => $isPinned && $p === Placement::BottomEnd,

            // ── Static mode (default): content-sized pill in document flow at the anchor hook ──
            'fi-ls-static' => $isStatic,

            // ── Relative mode: same visual as static, but position: relative so devs can offset via CSS ──
            'fi-ls-relative relative' => $isRelative,

            // Horizontal alignment for in-flow modes — w-fit + margin auto keeps the wrapper content-sized
            'w-fit' => $isInFlow,
            'mx-auto' => $isInFlow && in_array($p, [Placement::TopCenter, Placement::BottomCenter], true),
            'ms-auto' => $isInFlow && in_array($p, [Placement::TopEnd, Placement::BottomEnd], true),
        ])>
            @if ($displayMode === DisplayMode::Modal)
                @include('language-switch::partials.modal')
            @else
                @include('language-switch::partials.dropdown')
            @endif
        </div>
    @elseif ($displayMode === DisplayMode::Modal)
        @include('language-switch::partials.modal')
    @else
        @include('language-switch::partials.dropdown')
    @endif
</div>

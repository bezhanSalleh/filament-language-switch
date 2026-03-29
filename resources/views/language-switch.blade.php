@php
    $ls = \BezhanSalleh\LanguageSwitch\LanguageSwitch::make();
    $locales = $ls->getLocales();
    $isCircular = $ls->isCircular();
    $isFlagsOnly = $ls->isFlagsOnly();
    $hasFlags = filled($ls->getFlags());
    $displayMode = $ls->getDisplayMode();
    $renderContext = $ls->getRenderContext();
    $columns = $ls->getColumns();
    $maxHeight = $ls->getMaxHeight();
    $contentView = $ls->getContentView();
    $itemView = $ls->getItemView();
    $flagHeight = $ls->getFlagHeight();
    $charAvatarHeight = $ls->getCharAvatarHeight();

    $currentLocale = app()->getLocale();
    $currentLabel = $ls->getLabel($currentLocale);
    $currentFlag = $hasFlags ? $ls->getFlag($currentLocale) : null;

    $rtl = __('filament-panels::layout.direction') === 'rtl';
    $customPlacement = $ls->getDropdownPlacement();
    $placement = $customPlacement ?? ($rtl ? 'bottom-start' : 'bottom-end');

    // Deprecated: outside panel support
    $isVisibleOutsidePanels = $ls->isVisibleOutsidePanels();
    $outsidePanelsPlacement = $ls->getOutsidePanelPlacement()->value;
@endphp

<div @class(['fi-ls', 'fi-circular' => $isCircular, 'fi-flags-only' => $isFlagsOnly])>
    @if ($isVisibleOutsidePanels)
        {{-- Deprecated: outside panel fixed positioning --}}
        <div @class([
            'fixed w-fit flex p-4 z-50',
            'top-0' => str_contains($outsidePanelsPlacement, 'top'),
            'bottom-0' => str_contains($outsidePanelsPlacement, 'bottom'),
            'justify-start' => str_contains($outsidePanelsPlacement, 'left'),
            'justify-end' => str_contains($outsidePanelsPlacement, 'right'),
            'justify-center' => str_contains($outsidePanelsPlacement, 'center'),
        ])>
            <div class="rounded-lg bg-gray-50 dark:bg-gray-950">
                @include('language-switch::partials.dropdown')
            </div>
        </div>
    @elseif ($displayMode === \BezhanSalleh\LanguageSwitch\Enums\DisplayMode::Modal)
        @include('language-switch::partials.modal')
    @else
        @include('language-switch::partials.dropdown')
    @endif
</div>

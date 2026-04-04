@php
    $resolvedHook = $ls->getResolvedRenderHook();
    $isInSidebarNav = str_contains($resolvedHook, '::sidebar.nav.');
    $isInTopbarEdge = str_contains($resolvedHook, '::topbar.start') || str_contains($resolvedHook, '::topbar.end');

    $dropdownPlacement = match (true) {
        filled($customPlacement) => $customPlacement,
        $renderContext === 'sidebar' => 'top-start',
        $rtl => 'bottom-start',
        default => 'bottom-end',
    };
@endphp

<x-filament::dropdown
    :teleport="! $isInSidebarNav"
    :placement="$dropdownPlacement"
    :max-height="$maxHeight"
    @class([
        '-mx-2' => $isInSidebarNav,
        'mx-2' => $isInTopbarEdge,
        '[&_.fi-dropdown-panel]:w-fit' => $isFlagsOnly,
    ])
>
    <x-slot name="trigger">
        <x-language-switch::trigger />
    </x-slot>

    @include($contentView ?? 'language-switch::partials.list')
</x-filament::dropdown>

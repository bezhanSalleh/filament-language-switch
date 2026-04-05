@php
    $resolvedHook = $ls->getResolvedRenderHook();
    $isInSidebar = str_contains($resolvedHook, '::sidebar.') || str_contains($resolvedHook, 'user-menu.');
    $isInSidebarNav = str_contains($resolvedHook, '::sidebar.nav.');
    $isInTopbarEdge = str_contains($resolvedHook, '::topbar.end');

    $dropdownPlacement = match (true) {
        filled($customPlacement) => $customPlacement,
        $renderContext === 'sidebar' => 'top-start',
        $rtl => 'bottom-start',
        default => 'bottom-end',
    };
@endphp

<x-filament::dropdown
    :teleport="! $isInSidebar"
    :placement="$dropdownPlacement"
    :max-height="$maxHeight"
    @class([
        '-mx-2' => $isInSidebarNav,
        'ms-2' => $isInTopbarEdge,
        '[&_.fi-dropdown-panel]:w-fit' => $isFlagsOnly,
    ])
>
    <x-slot name="trigger">
        <x-language-switch::trigger />
    </x-slot>

    @include($contentView ?? 'language-switch::partials.list')
</x-filament::dropdown>

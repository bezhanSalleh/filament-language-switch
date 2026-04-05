<x-filament::dropdown
    :teleport="$layout->shouldTeleport"
    :placement="$layout->placement"
    :max-height="$maxHeight"
    @class([
        '-mx-2' => $layout->spacingKey === 'sidebar-nav',
        'mx-2' => $layout->spacingKey === 'topbar-edge',
        '-me-2' => $layout->spacingKey === 'user-menu-before',
        '-ms-2' => $layout->spacingKey === 'user-menu-after',
        '[&_.fi-dropdown-panel]:overscroll-y-contain',
        '[&_.fi-dropdown-panel]:w-fit' => $isFlagsOnly,
    ])
>
    <x-slot name="trigger">
        <x-language-switch::trigger :layout="$layout" />
    </x-slot>

    @include('language-switch::partials.list')
</x-filament::dropdown>

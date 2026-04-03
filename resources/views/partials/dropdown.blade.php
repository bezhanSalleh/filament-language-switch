@php
    $inSidebar = str_contains($ls->getResolvedRenderHook(), '::sidebar.');

    $dropdownPlacement = match (true) {
        filled($customPlacement) => $customPlacement,
        $renderContext === 'sidebar' => 'top-end',
        $rtl => 'bottom-start',
        default => 'bottom-end',
    };
@endphp

<x-filament::dropdown
    :teleport="! $inSidebar"
    :placement="$dropdownPlacement"
    :max-height="$maxHeight"
    @class(['[&_.fi-dropdown-panel]:w-fit' => $isFlagsOnly])
>
    <x-slot name="trigger">
        <x-language-switch::trigger />
    </x-slot>

    @include($contentView ?? 'language-switch::partials.list')
</x-filament::dropdown>

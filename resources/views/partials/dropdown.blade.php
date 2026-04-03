@php
    $dropdownPlacement = match (true) {
        filled($customPlacement) => $customPlacement,
        in_array($renderContext, ['nav', 'sidebar']) => 'top-start',
        $rtl => 'bottom-start',
        default => 'bottom-end',
    };
@endphp

<x-filament::dropdown
    :teleport="$renderContext === 'topbar' && ! str_contains($ls->getResolvedRenderHook(), '::sidebar.')"
    :placement="$dropdownPlacement"
    :max-height="$maxHeight"
    @class([
        'w-full' => $renderContext === 'nav' && ! $isFlagsOnly,
        '[&_.fi-dropdown-panel]:w-fit' => $isFlagsOnly,
    ])
>
    <x-slot name="trigger">
        <x-language-switch::trigger />
    </x-slot>

    @include($contentView ?? 'language-switch::partials.list')
    
</x-filament::dropdown>

@php
    $dropdownPlacement = match (true) {
        filled($customPlacement) => $customPlacement,
        in_array($renderContext, ['nav', 'sidebar']) => 'top-start',
        $rtl => 'bottom-start',
        default => 'bottom-end',
    };
@endphp

<x-filament::dropdown
    :teleport="$renderContext === 'topbar'"
    :placement="$dropdownPlacement"
    :max-height="$maxHeight"
    @class(['w-full' => in_array($renderContext, ['nav', 'sidebar'])])
>
    <x-slot name="trigger">
        <x-language-switch::trigger />
    </x-slot>

    @include($contentView ?? 'language-switch::partials.list')
</x-filament::dropdown>

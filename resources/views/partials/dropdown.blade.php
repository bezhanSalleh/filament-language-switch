@php
    $dropdownPlacement = match (true) {
        filled($customPlacement) => $customPlacement,
        in_array($renderContext, ['nav', 'sidebar']) => ($rtl ? 'left-start' : 'right-start'),
        $rtl => 'bottom-start',
        default => 'bottom-end',
    };
@endphp

<x-filament::dropdown
    teleport
    :placement="$dropdownPlacement"
    :max-height="$maxHeight"
>
    <x-slot name="trigger">
        @include('language-switch::partials.trigger')
    </x-slot>

    @include($contentView ?? 'language-switch::partials.list')
</x-filament::dropdown>

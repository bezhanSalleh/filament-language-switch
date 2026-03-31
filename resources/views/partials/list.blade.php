@php
    $isModal = $displayMode === \BezhanSalleh\LanguageSwitch\Enums\DisplayMode::Modal;
@endphp

@if ($isModal)
    {{-- Modal/Slide-over: radio cards or flag showcase --}}
    <div
        @class([
            'grid gap-4',
            'place-content-center' => $isFlagsOnly,
        ])
        @style(["grid-template-columns: repeat({$columns}, minmax(0, 1fr))" => $columns > 1])
    >
        @foreach ($locales as $locale)
            @include($itemView ?? 'language-switch::components.locale-item', [
                'locale' => $locale,
                'label' => $ls->getLabel($locale),
                'flag' => $hasFlags ? $ls->getFlag($locale) : null,
                'charAvatar' => ! $hasFlags ? $ls->getCharAvatar($locale) : null,
                'isFlagsOnly' => $isFlagsOnly,
                'isCircular' => $isCircular,
                'isModal' => true,
                'flagHeight' => $flagHeight,
                'charAvatarHeight' => $charAvatarHeight,
            ])
        @endforeach
    </div>
@else
    {{-- Dropdown: standard list rows --}}
    <x-filament::dropdown.list>
        @foreach ($locales as $locale)
            @include($itemView ?? 'language-switch::components.locale-item', [
                'locale' => $locale,
                'label' => $ls->getLabel($locale),
                'flag' => $hasFlags ? $ls->getFlag($locale) : null,
                'charAvatar' => ! $hasFlags ? $ls->getCharAvatar($locale) : null,
                'isFlagsOnly' => $isFlagsOnly,
                'isCircular' => $isCircular,
                'isModal' => false,
                'flagHeight' => $flagHeight,
                'charAvatarHeight' => $charAvatarHeight,
            ])
        @endforeach
    </x-filament::dropdown.list>
@endif

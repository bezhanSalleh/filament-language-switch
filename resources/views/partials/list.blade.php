@php
    $isModal = $displayMode === \BezhanSalleh\LanguageSwitch\Enums\DisplayMode::Modal;
@endphp

@if ($isModal)
    {{-- Modal/Slide-over: radio cards or flag showcase --}}
    <div
        @class([
            'grid',
            'gap-4' => ! $isFlagsOnly,
            'gap-4 justify-center' => $isFlagsOnly,
        ])
        @style([
            "grid-template-columns: repeat({$columns}, minmax(0, 1fr))" => ! $isFlagsOnly && $columns > 1,
            "grid-template-columns: repeat(auto-fit, minmax(5rem, 1fr))" => $isFlagsOnly && $columns <= 1,
            "grid-template-columns: repeat({$columns}, 6rem)" => $isFlagsOnly && $columns > 1,
        ])
    >
        @foreach ($locales as $locale)
            @include('language-switch::components.locale-item', [
                'locale' => $locale,
                'label' => $ls->getLabel($locale),
                'flag' => $hasFlags ? $ls->getFlag($locale) : null,
                'avatar' => ! $hasFlags ? $ls->getAvatar($locale) : null,
                'isFlagsOnly' => $isFlagsOnly,
                'isCircular' => $isCircular,
                'isModal' => true,
                'flagHeight' => $flagHeight,
                'avatarHeight' => $avatarHeight,
            ])
        @endforeach
    </div>
@else
    {{-- Dropdown: standard list rows --}}
    <x-filament::dropdown.list>
        @foreach ($locales as $locale)
            @include('language-switch::components.locale-item', [
                'locale' => $locale,
                'label' => $ls->getLabel($locale),
                'flag' => $hasFlags ? $ls->getFlag($locale) : null,
                'avatar' => ! $hasFlags ? $ls->getAvatar($locale) : null,
                'isFlagsOnly' => $isFlagsOnly,
                'isCircular' => $isCircular,
                'isModal' => false,
                'flagHeight' => $flagHeight,
                'avatarHeight' => $avatarHeight,
            ])
        @endforeach
    </x-filament::dropdown.list>
@endif

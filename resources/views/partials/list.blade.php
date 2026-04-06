@php
    use BezhanSalleh\LanguageSwitch\Enums\ItemStyle;

    $isModal = $displayMode === \BezhanSalleh\LanguageSwitch\Enums\DisplayMode::Modal;
    $isCompact = $itemStyle->isCompact();

    // In modal mode, AvatarOnly falls back to LabelOnly — the avatar is too
    // small for a showcase card. Flag and label styles render as expected.
    $showFlag = $itemStyle->hasVisual() && $hasFlags;
    $showAvatar = $itemStyle->hasVisual()
        && ! $hasFlags
        && ! ($isModal && $itemStyle === ItemStyle::AvatarOnly);
@endphp

@if ($isModal)
    {{-- Modal/Slide-over: radio cards or flag showcase --}}
    <div
        @class([
            'grid',
            'gap-4' => ! $isCompact,
            'gap-4 justify-center' => $isCompact,
        ])
        @style([
            "grid-template-columns: repeat({$columns}, minmax(0, 1fr))" => ! $isCompact && $columns > 1,
            "grid-template-columns: repeat(auto-fit, minmax(5rem, 1fr))" => $isCompact && $columns <= 1,
            "grid-template-columns: repeat({$columns}, 6rem)" => $isCompact && $columns > 1,
        ])
    >
        @foreach ($locales as $locale)
            @include('language-switch::components.locale-item', [
                'locale' => $locale,
                'label' => $ls->getLabel($locale),
                'flag' => $showFlag ? $ls->getFlag($locale) : null,
                'avatar' => $showAvatar ? $ls->getAvatar($locale) : null,
                'isCompact' => $isCompact,
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
                'flag' => $showFlag ? $ls->getFlag($locale) : null,
                'avatar' => $showAvatar ? $ls->getAvatar($locale) : null,
                'isCompact' => $isCompact,
                'isCircular' => $isCircular,
                'isModal' => false,
                'flagHeight' => $flagHeight,
                'avatarHeight' => $avatarHeight,
            ])
        @endforeach
    </x-filament::dropdown.list>
@endif

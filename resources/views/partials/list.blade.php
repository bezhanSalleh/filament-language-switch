<x-filament::dropdown.list
    @class(['grid gap-2' => $columns > 1])
    @style(["grid-template-columns: repeat({$columns}, minmax(0, 1fr))" => $columns > 1])
>
    @foreach ($locales as $locale)
        @continue(app()->isLocale($locale))

        @include($itemView ?? 'language-switch::components.locale-item', [
            'locale' => $locale,
            'label' => $ls->getLabel($locale),
            'flag' => $hasFlags ? $ls->getFlag($locale) : null,
            'charAvatar' => ! $hasFlags ? $ls->getCharAvatar($locale) : null,
            'isFlagsOnly' => $isFlagsOnly,
            'isCircular' => $isCircular,
        ])
    @endforeach
</x-filament::dropdown.list>

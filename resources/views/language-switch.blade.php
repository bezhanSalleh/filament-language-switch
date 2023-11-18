@php
    $locales = $languageSwitch->getLocales();
    $isCircular = $languageSwitch->isCircular();
    $isFlagsOnly = $languageSwitch->isFlagsOnly();
@endphp

<x-filament::dropdown
    teleport
    placement="bottom"
    :width="$isFlagsOnly ? 'flags-only' : null"
    class="fi-dropdown fi-user-menu"
>
    <x-slot name="trigger">
        <div
            @class([
                'flex items-center justify-center text-sm font-semibold w-9 h-9 language-switch-trigger text-primary-500 bg-primary-500/10',
                'rounded-full' => $isCircular,
                'rounded-lg' => !$isCircular,
                'p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400' => $isFlagsOnly,
            ])
            @if ($isFlagsOnly)
            x-tooltip="{
                content: @js($languageSwitch->getLabel(app()->getLocale())),
                theme: $store.theme,
                placement: 'bottom'
            }"
            @endif
        >
            @if ($isFlagsOnly)
                <img
                    src="{{ $languageSwitch->getFlag(app()->getLocale()) }}"
                    @class([
                        'object-cover object-center max-w-none',
                        'rounded-full' => $isCircular,
                        'rounded-md' => !$isCircular,
                    ])
                />
            @else
                <span>{{ $languageSwitch->getFlag(app()->getLocale()) }}</span>
            @endif
        </div>
    </x-slot>

    <x-filament::dropdown.list @class(['!border-t-0 space-y-1'])>
        @foreach ($locales as $locale)
            @if (!app()->isLocale($locale))
                <button
                    type="button"
                    wire:click="changeLocale('{{ $locale }}')"
                    @if ($isFlagsOnly)
                    x-tooltip="{
                        content: @js($languageSwitch->getLabel($locale)),
                        theme: $store.theme,
                        placement: 'right'
                    }"
                    @endif

                    @class([
                        'flex items-center justify-center w-full px-2 py-0.5 text-sm transition-colors duration-75 rounded-md outline-none fi-dropdown-list-item whitespace-nowrap disabled:pointer-events-none disabled:opacity-70 fi-dropdown-list-item-color-gray hover:bg-gray-950/5 focus:bg-gray-950/5 dark:hover:bg-white/5 dark:focus:bg-white/5',
                    ])
                >

                    @if ($isFlagsOnly)
                        <img
                            src="{{ $languageSwitch->getFlag($locale) }}"
                            @class([
                                'object-cover object-center max-w-none w-9 h-9 p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400',
                                'rounded-full' => $isCircular,
                                'rounded-lg' => !$isCircular,
                            ])
                        />
                    @else
                        <span
                            @class([
                                'flex items-center justify-center flex-shrink-0 w-6 h-6 p-4 text-xs font-semibold group-hover:bg-white group-hover:text-primary-600 group-hover:border group-hover:border-primary-500/10 group-focus:text-white bg-primary-500/10 text-primary-500',
                                'rounded-full' => $isCircular,
                                'rounded-lg' => !$isCircular,
                            ])
                        >
                            {{ $languageSwitch->getFlag($locale) }}
                        </span>

                        <span class="text-gray-700 hover:bg-transparent dark:text-gray-200">
                            {{ $languageSwitch->getLabel($locale) }}
                        </span>

                    @endif
                </button>
            @endif
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>

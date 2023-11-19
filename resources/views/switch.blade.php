<x-filament::dropdown
    teleport
    :placement="$placement"
    :width="$isFlagsOnly ? 'flags-only' : 'fls-dropdown-width'"
    class="fi-dropdown fi-user-menu"
>
    <x-slot name="trigger">
        <div
            @class([
                'flex items-center justify-center text-sm font-semibold w-9 h-9 language-switch-trigger text-primary-600 bg-primary-500/10',
                'rounded-full' => $isCircular,
                'rounded-lg' => !$isCircular,
                'p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400' => $isFlagsOnly || $hasFlags,
            ])
            x-tooltip="{
                content: @js($languageSwitch->getLabel(app()->getLocale())),
                theme: $store.theme,
                placement: 'bottom'
            }"
        >
            @if ($isFlagsOnly || $hasFlags)
                <x-filament-language-switch::flag
                    :src="$languageSwitch->getFlag(app()->getLocale())"
                    :circular="$isCircular"
                    :alt="$languageSwitch->getLabel(app()->getLocale())"
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
                        'flex items-center w-full px-2 py-0.5 text-sm transition-colors duration-75 rounded-md outline-none fi-dropdown-list-item whitespace-nowrap disabled:pointer-events-none disabled:opacity-70 fi-dropdown-list-item-color-gray hover:bg-gray-950/5 focus:bg-gray-950/5 dark:hover:bg-white/5 dark:focus:bg-white/5',
                        'justify-center' => $isFlagsOnly,
                        'justify-start space-x-1' => !$isFlagsOnly,
                    ])
                >

                    @if ($isFlagsOnly)
                        <x-filament-language-switch::flag
                            :src="$languageSwitch->getFlag($locale)"
                            :circular="$isCircular"
                            :alt="$languageSwitch->getLabel($locale)"
                            class="w-7 h-7"
                        />
                    @else
                        @if ($hasFlags)
                            <x-filament-language-switch::flag
                                :src="$languageSwitch->getFlag($locale)"
                                :circular="$isCircular"
                                :alt="$languageSwitch->getLabel($locale)"
                                class="p-1 !rounded-lg w-7 h-7"
                            />
                        @else
                            <span
                                @class([
                                    'flex items-center justify-center flex-shrink-0 w-6 h-6 p-4 text-xs font-semibold group-hover:bg-white group-hover:text-primary-600 group-hover:border group-hover:border-primary-500/10 group-focus:text-white bg-primary-500/10 text-primary-600',
                                    'rounded-full' => $isCircular,
                                    'rounded-lg' => !$isCircular,
                                ])
                            >
                                {{ $languageSwitch->getFlag($locale) }}
                            </span>
                        @endif
                        <span class="font-medium text-gray-600 hover:bg-transparent dark:text-gray-200">
                            {{ $languageSwitch->getLabel($locale) }}
                        </span>

                    @endif
                </button>
            @endif
        @endforeach
    </x-filament::dropdown.list>
</x-filament::dropdown>
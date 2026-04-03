@props([
    'locale',
    'label',
    'flag' => null,
    'charAvatar' => null,
    'isFlagsOnly' => false,
    'isCircular' => false,
    'isModal' => false,
    'flagHeight' => 'h-16',
    'charAvatarHeight' => 'size-8',
])

@php
    $isActive = app()->isLocale($locale);
@endphp

@if ($isModal && $isFlagsOnly && $flag)
    {{-- Modal flagsOnly: Radio-Group Card --}}
    <button
        type="button"
        @if (! $isActive)
            wire:click="changeLocale('{{ $locale }}')"
        @endif
        x-tooltip="{
            content: @js($label),
            theme: $store.theme,
        }"
        @class([
            'fi-ls-item group relative flex items-center justify-center rounded-xl p-4 transition-all duration-200 outline-none',
            'ring-1 ring-primary-600 bg-primary-50 shadow-sm dark:ring-primary-500 dark:bg-primary-400/10 pointer-events-none' => $isActive,
            'ring-1 ring-gray-950/8 bg-white hover:ring-gray-950/15 hover:shadow-sm dark:ring-white/8 dark:bg-white/5 dark:hover:ring-white/15' => ! $isActive,
        ])
    >
        @if ($isActive)
            <span class="absolute -top-1.5 -end-1.5 z-10 flex size-5 items-center justify-center rounded-full ring-2 ring-white bg-primary-600 shadow-sm dark:ring-gray-900 dark:bg-primary-500">
                <x-filament::icon
                    icon="heroicon-s-check"
                    class="size-3 text-white"
                />
            </span>
        @endif

        <img
            src="{{ $flag }}"
            alt="{{ $label }}"
            loading="lazy"
            class="h-10 w-16 rounded-sm object-cover shadow-sm transition-transform duration-200 group-hover:scale-105"
        />
    </button>

@elseif ($isModal)
    {{-- Modal/Slide-over: radio card style --}}
    <button
        type="button"
        @if (! $isActive)
            wire:click="changeLocale('{{ $locale }}')"
        @endif
        @class([
            'fi-ls-item relative group flex items-center shadow-sm gap-3 rounded-lg p-3 transition duration-75 outline-none',
            'ring-1 ring-primary-600 bg-primary-50 dark:ring-primary-500/20 dark:bg-primary-400/10 pointer-events-none' => $isActive,
            'ring-1 ring-gray-950/8 bg-white hover:ring-primary-500/30 dark:ring-white/8 dark:bg-white/5 dark:hover:ring-primary-500/30 hover:ring-2' => ! $isActive,
        ])
    >
        @if ($isActive)
            <x-filament::icon
                icon="heroicon-s-check-circle"
                class="absolute top-2 end-2 h-4 w-4 text-primary-600 dark:text-primary-500"
            />
        @endif

        @if ($flag)
            <x-language-switch::flag
                :src="$flag"
                :alt="$label"
                @class(['group-hover:scale-105 transition-transform duration-150 ease-in', 'fi-circular' => $isCircular])
            />
        @elseif ($charAvatar)
            <x-language-switch::char-avatar
                :locale="$locale"
                :active="$isActive"
                :class="$charAvatarHeight"
            />
        @endif

        <span @class([
            'text-sm font-medium',
            'text-primary-600 dark:text-primary-400' => $isActive,
            'text-gray-700 dark:text-gray-200' => ! $isActive,
        ])>
            {{ $label }}
        </span>
    </button>

@else
    {{-- Dropdown: list row style --}}
    <button
        type="button"
        @if (! $isActive)
            wire:click="changeLocale('{{ $locale }}')"
        @endif
        @if ($isFlagsOnly)
            x-tooltip="{
                content: @js($label),
                theme: $store.theme,
            }"
        @endif
        @class([
            'fi-dropdown-list-item fi-ls-item whitespace-nowrap',
            'bg-primary-50 dark:bg-primary-400/10 pointer-events-none' => $isActive,
            'justify-center p-1.5' => $isFlagsOnly,
        ])
    >
        @if ($flag)
            <x-language-switch::flag
                :src="$flag"
                :alt="$label"
                @class([
                    $isFlagsOnly ? 'h-7 w-11 rounded-sm! object-cover' : 'fi-size-sm',
                    'fi-circular' => $isCircular && ! $isFlagsOnly,
                ])
            />
        @elseif ($charAvatar)
            <x-language-switch::char-avatar
                :locale="$locale"
                :active="$isActive"
                @class([
                    'size-6',
                    'rounded-full' => $isCircular,
                ])
            />
        @endif

        @if ($isFlagsOnly)
        @else
            <span @class([
                'fi-dropdown-list-item-label',
                'text-primary-600 dark:text-primary-400' => $isActive,
            ])>
                {{ $label }}
            </span>

            @if ($isActive)
                <x-filament::icon
                    icon="heroicon-m-check-circle"
                    class="ms-auto h-5 w-5 shrink-0 text-primary-600 dark:text-primary-400"
                />
            @endif
        @endif
    </button>
@endif

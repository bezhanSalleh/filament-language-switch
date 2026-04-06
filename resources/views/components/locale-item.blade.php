@props([
    'locale',
    'label',
    'flag' => null,
    'avatar' => null,
    'isCompact' => false,
    'isCircular' => false,
    'isModal' => false,
    'flagHeight' => 'h-16',
    'avatarHeight' => 'size-8',
])

@php
    $isActive = app()->isLocale($locale);
@endphp

@if ($isModal && $isCompact && $flag)
    {{-- Modal compact (FlagOnly): Radio-Group Card --}}
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
            'fi-ls-item group relative rounded-xl shadow-sm p-2 outline-none overflow-visible bg-gray-50 ring-1 dark:bg-gray-400/10',
            'ring-primary-500/50 dark:ring-primary-400 pointer-events-none' => $isActive,
            'ring-gray-950/5 dark:ring-white/10 hover:ring-gray-950/10 dark:hover:ring-white/15 transition-colors duration-75 ease-in' => ! $isActive,
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
            @class(['block w-full rounded-lg object-cover group-hover:scale-105 transition-transform duration-75 ease-in', $flagHeight])
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
            'ring-1 ring-primary-500/20 bg-primary-50 dark:ring-primary-400/20 dark:bg-primary-400/10 pointer-events-none' => $isActive,
            'ring-1 ring-gray-950/8 bg-white hover:ring-primary-500/30 hover:bg-primary-50 dark:ring-white/8 dark:bg-white/5 dark:hover:ring-primary-400/20 dark:hover:bg-primary-400/10' => ! $isActive,
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
        @elseif ($avatar)
            <span @class([
                'fi-ls-avatar flex items-center justify-center shrink-0 font-semibold text-xs',
                $avatarHeight,
                'rounded-full' => $isCircular,
                'rounded-md' => ! $isCircular,
                'bg-gray-50 dark:bg-white/5 text-primary-600 dark:text-primary-400' => $isActive,
                'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400 group-hover:bg-white group-hover:text-primary-500 dark:group-hover:text-primary-400 dark:group-hover:bg-white/10' => ! $isActive,
            ])>
                {{ $avatar }}
            </span>
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
        @if ($isCompact)
            x-tooltip="{
                content: @js($label),
                theme: $store.theme,
            }"
        @endif
        @class([
            'fi-dropdown-list-item fi-ls-item whitespace-nowrap',
            'bg-primary-50 dark:bg-primary-400/10 pointer-events-none' => $isActive,
            'justify-center p-1.5' => $isCompact,
        ])
    >
        @if ($flag)
            <x-language-switch::flag
                :src="$flag"
                :alt="$label"
                @class([
                    $isCompact ? 'h-7 w-11 rounded-sm! object-cover' : 'fi-size-sm',
                    'fi-circular' => $isCircular && ! $isCompact,
                ])
            />
        @elseif ($avatar)
            <span @class([
                'fi-ls-avatar flex size-6 items-center justify-center shrink-0 font-semibold text-xs',
                'rounded-full' => $isCircular,
                'rounded-md' => ! $isCircular,
                'bg-primary-500/20 text-primary-600 dark:text-primary-400' => $isActive,
                'bg-gray-100 text-gray-600 dark:bg-white/5 dark:text-gray-400' => ! $isActive,
            ])>
                {{ $avatar }}
            </span>
        @endif

        @unless ($isCompact)
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
        @endunless
    </button>
@endif

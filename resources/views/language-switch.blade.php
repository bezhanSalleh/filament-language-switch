@php
    $locales = $languageSwitch->getLocales();
@endphp
<x-filament::dropdown
    teleport
    placement="bottom"
    :width="$languageSwitch->isFlagsOnly() ? 'flags-only' : null"
    class="fi-dropdown fi-user-menu"
>
    <style>
        .flags-only {
            max-width: 3rem!important;
        }
    </style>

    <x-slot name="trigger" class="">
        <div
            @class([
                "flex items-center justify-center text-sm font-semibold w-9 h-9 language-switch-trigger text-primary-500 bg-primary-500/10",
                "rounded-full" => $languageSwitch->isCircular(),
                "rounded-lg" => !$languageSwitch->isCircular(),
                "p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400" => $languageSwitch->isFlagsOnly(),
            ])
            @if ($languageSwitch->isFlagsOnly())
                x-tooltip="{
                    content: @js($languageSwitch->getLabel(app()->getLocale())),
                    theme: $store.theme,
                    placement: 'bottom'
                }"
            @endif
        >
            @if ($languageSwitch->isFlagsOnly())
            <img
                src="{{ $languageSwitch->getFlag(app()->getLocale()) }}"
                @class([
                    "object-cover object-center max-w-none",
                    "rounded-full" => $languageSwitch->isCircular(),
                    "rounded-md" => !$languageSwitch->isCircular()
                ])
            />
            @else
                <span>{{ $languageSwitch->getFlag(app()->getLocale()) }}</span>
            @endif
        </div>
    </x-slot>
    <x-filament::dropdown.list
        @class([
            "!border-t-0 space-y-1",
        ])
    >
        @foreach ($locales as $locale)
            @if (!app()->isLocale($locale))
                <button
                    type="button"
                    wire:click="changeLocale('{{ $locale }}')"
                    @if ($languageSwitch->isFlagsOnly())
                    x-tooltip="{
                        content: @js($languageSwitch->getLabel($locale)),
                        theme: $store.theme,
                        placement: 'right'
                    }"
                    @endif
                    @class([
                        "flex items-center justify-center w-full px-2 py-0.5 text-sm transition-colors duration-75 rounded-md outline-none fi-dropdown-list-item whitespace-nowrap disabled:pointer-events-none disabled:opacity-70 fi-dropdown-list-item-color-gray hover:bg-gray-950/5 focus:bg-gray-950/5 dark:hover:bg-white/5 dark:focus:bg-white/5",

                    ])
                >

                    @if ($languageSwitch->isFlagsOnly())
                            <img
                                src="{{ $languageSwitch->getFlag($locale) }}"
                                @class([
                                    "object-cover object-center max-w-none w-9 h-9 p-1 ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400",
                                    "rounded-full" => $languageSwitch->isCircular(),
                                    "rounded-lg" => !$languageSwitch->isCircular()
                                ])
                            />
                    @else

                        <span
                            @class([
                                "flex items-center justify-center flex-shrink-0 w-6 h-6 p-4 text-xs font-semibold group-hover:bg-white group-hover:text-primary-600 group-hover:border group-hover:border-primary-500/10 group-focus:text-white bg-primary-500/10 text-primary-500",
                                "rounded-full" => $languageSwitch->isCircular(),
                                "rounded-lg" => !$languageSwitch->isCircular()
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
{{-- <x-filament::dropdown>
    <x-slot name="trigger">
        <x-filament::button>
            More actions
        </x-filament::button>
    </x-slot>

    <x-filament::dropdown.list>
        <x-filament::dropdown.list.item
            wire:click="openViewModal"
            :image="$languageSwitch->getFlag(app()->getLocale())"
            :tooltip="$languageSwitch->getLabel(app()->getLocale())"
        >
            {{ $languageSwitch->getLabel(app()->getLocale()) }}
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item wire:click="openEditModal" icon="heroicon-m-pencil">
            Edit
        </x-filament::dropdown.list.item>

        <x-filament::dropdown.list.item wire:click="openDeleteModal">
            Delete
        </x-filament::dropdown.list.item>
    </x-filament::dropdown.list>
</x-filament::dropdown> --}}
@php
    $attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag;
    $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();
@endphp

@switch($renderContext)
    @case('nav')
        {{--
            Inside sidebar nav (SIDEBAR_NAV_START/END).
            Matches fi-sidebar-item-btn: relative flex items-center justify-center gap-x-3 rounded-lg p-2
            Icon: fi-sidebar-item-icon with IconSize::Large
            Label: fi-sidebar-item-label (flex-1 truncate text-sm font-medium)
        --}}
        <button
            type="button"
            @if ($isSidebarCollapsibleOnDesktop)
                x-data="{ tooltip: false }"
                x-effect="
                    tooltip = $store.sidebar.isOpen
                        ? false
                        : {
                              content: @js($currentLabel),
                              placement: document.dir === 'rtl' ? 'left' : 'right',
                              theme: $store.theme,
                          }
                "
                x-tooltip.html="tooltip"
            @endif
            {{
                $attributes->class([
                    'fi-sidebar-item-btn fi-ls-trigger',
                ])
            }}
        >
            {{
                \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::Language, attributes: new \Illuminate\View\ComponentAttributeBag([
                    'class' => 'fi-sidebar-item-icon',
                ]), size: \Filament\Support\Enums\IconSize::Large)
            }}

            <span
                @if ($isSidebarCollapsibleOnDesktop)
                    x-show="$store.sidebar.isOpen"
                    x-transition:enter="fi-transition-enter"
                    x-transition:enter-start="fi-transition-enter-start"
                    x-transition:enter-end="fi-transition-enter-end"
                @endif
                class="fi-sidebar-item-label"
            >
                {{ $currentLabel }}
            </span>
        </button>
        @break

    @case('sidebar')
        {{--
            Sidebar footer area (SIDEBAR_FOOTER, USER_MENU_BEFORE/AFTER).
            Matches fi-sidebar-database-notifications-btn used by Notifications and Switch Panels.
        --}}
        <button
            type="button"
            @if ($isSidebarCollapsibleOnDesktop)
                x-data="{ tooltip: false }"
                x-effect="
                    tooltip = $store.sidebar.isOpen
                        ? false
                        : {
                              content: @js($currentLabel),
                              placement: document.dir === 'rtl' ? 'left' : 'right',
                              theme: $store.theme,
                          }
                "
                x-tooltip.html="tooltip"
            @endif
            {{
                $attributes->class([
                    'fi-sidebar-database-notifications-btn fi-ls-trigger',
                ])
            }}
        >
            {{
                \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::Language, attributes: new \Illuminate\View\ComponentAttributeBag([
                    'class' => 'h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500',
                ]))
            }}

            <span
                @if ($isSidebarCollapsibleOnDesktop)
                    x-show="$store.sidebar.isOpen"
                    x-transition:enter="fi-transition-enter"
                    x-transition:enter-start="fi-transition-enter-start"
                    x-transition:enter-end="fi-transition-enter-end"
                @endif
                class="fi-sidebar-database-notifications-btn-label"
            >
                {{ $currentLabel }}
            </span>
        </button>
        @break

    @case('user-menu')
        {{--
            Inside user menu dropdown (USER_MENU_PROFILE_BEFORE/AFTER).
            Matches fi-dropdown-list-item used by other user menu items.
        --}}
        <button
            type="button"
            aria-label="{{ $currentLabel }}"
            {{
                $attributes->class([
                    'fi-dropdown-list-item fi-ls-trigger',
                ])
            }}
        >
            {{
                \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::Language, attributes: new \Illuminate\View\ComponentAttributeBag([
                    'class' => 'fi-dropdown-list-item-icon',
                ]))
            }}
            <span class="fi-dropdown-list-item-label">{{ $currentLabel }}</span>
        </button>
        @break

    @default
        {{--
            Topbar (GLOBAL_SEARCH_*, TOPBAR_*).
            Matches fi-icon-btn used by panel-switch topbar trigger.
        --}}
        <button
            type="button"
            aria-label="{{ $currentLabel }}"
            x-tooltip="{
                content: @js($currentLabel),
                theme: $store.theme,
            }"
            {{
                $attributes->class([
                    'fi-icon-btn fi-ls-trigger bg-gray-100 rounded-full! dark:bg-gray-800',
                ])
            }}
            style="min-width: 36px;"
        >
            @if ($hasFlags || $isFlagsOnly)
                <x-language-switch::flag
                    :src="$currentFlag"
                    :alt="$currentLabel"
                    @class([
                        'h-6 w-6',
                        'rounded-full' => $isCircular,
                        'rounded-md' => ! $isCircular,
                    ])
                />
            @else
                <x-language-switch::char-avatar
                    :locale="$currentLocale"
                    class="h-6 w-6"
                />
            @endif
        </button>
@endswitch

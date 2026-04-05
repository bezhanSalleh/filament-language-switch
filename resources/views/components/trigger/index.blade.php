@props(['layout'])

@if ($layout->triggerShell === 'dropdown-list-item')
    {{-- User menu variant --}}
    @if ($layout->contentType === 'flag' && $layout->isUrlFlag)
        <x-filament::dropdown.list.item
            :image="$layout->flagSrc"
            {{ $attributes->class(['fi-ls-trigger']) }}
        >
            {{ $layout->currentLabel }}
        </x-filament::dropdown.list.item>
    @elseif ($layout->contentType === 'avatar')
        <button {{ $attributes->class(['fi-dropdown-list-item fi-ls-trigger']) }}>
            <span class="flex size-5 items-center justify-center shrink-0 font-semibold text-xs text-gray-400 dark:text-gray-500">
                {{ $layout->currentAvatar }}
            </span>
            <span class="fi-dropdown-list-item-label">{{ $layout->currentLabel }}</span>
        </button>
    @elseif ($layout->contentType === 'label')
        <button {{ $attributes->class(['fi-dropdown-list-item fi-ls-trigger']) }}>
            <span class="fi-dropdown-list-item-label">{{ $layout->currentLabel }}</span>
        </button>
    @else
        <x-filament::dropdown.list.item
            :icon="$layout->triggerIcon"
            icon-alias="language-switch::trigger"
            {{ $attributes->class(['fi-ls-trigger']) }}
        >
            {{ $layout->currentLabel }}
        </x-filament::dropdown.list.item>
    @endif
@else
    {{-- Button variant: topbar + sidebar --}}
    <button
        type="button"
        aria-label="{{ $layout->currentLabel }}"
        @if (! $layout->hasLabel)
            x-tooltip="{ content: @js($layout->currentLabel), theme: $store.theme }"
        @endif
        @if ($layout->shouldHideWhenCollapsed)
            x-show="$store.sidebar.isOpen"
            x-cloak
        @endif
        {{
            $attributes->class([
                'fi-ls-trigger',
                // Topbar shell
                'flex shrink-0 items-center h-9 gap-x-2 bg-gray-100 dark:bg-gray-800 transition duration-75 outline-none' => $layout->renderContext === 'topbar',
                'w-9 justify-center' => $layout->renderContext === 'topbar' && ! $layout->hasLabel,
                'px-3' => $layout->renderContext === 'topbar' && $layout->hasLabel,
                'rounded-full' => $layout->renderContext === 'topbar' && $layout->isCircular,
                'rounded-lg' => $layout->renderContext === 'topbar' && ! $layout->isCircular,
                // Sidebar shell — nav area uses fi-sidebar-item-btn (matches Welcome, Dashboard),
                // footer area uses fi-sidebar-database-notifications-btn (matches notifications, switch panels).
                // w-full because the button is a flex item inside .fi-dropdown-trigger and wouldn't
                // naturally fill the width the way Filament's <a> inside <li> does.
                // Hover/focus classes are added explicitly because Filament's .fi-sidebar-item-btn
                // hover CSS is scoped to .fi-sidebar-item.fi-sidebar-item-has-url > .fi-sidebar-item-btn,
                // which requires an <li> parent wrapper we don't have.
                'fi-sidebar-item-btn w-full hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5' => $layout->sidebarVariant === 'nav-item',
                'fi-sidebar-database-notifications-btn' => $layout->sidebarVariant === 'footer-item',
            ])
        }}
    >
        {{-- Visual content (omitted when contentType === 'label') --}}
        @if ($layout->hasVisual)
            @if ($layout->contentType === 'flag' && $layout->isUrlFlag)
                <x-filament::avatar
                    :src="$layout->flagSrc"
                    :alt="$layout->currentLabel"
                    size="sm"
                    :circular="$layout->isCircular"
                />
            @elseif ($layout->contentType === 'avatar')
                <span class="flex size-5 items-center justify-center shrink-0 font-semibold text-xs text-primary-500 dark:text-primary-400">
                    {{ $layout->currentAvatar }}
                </span>
            @else
                @if ($layout->sidebarVariant === 'nav-item')
                    {{
                        \Filament\Support\generate_icon_html($layout->triggerIcon, 'language-switch::trigger', new \Illuminate\View\ComponentAttributeBag([
                            'class' => 'fi-sidebar-item-icon',
                        ]), size: \Filament\Support\Enums\IconSize::Large)
                    }}
                @elseif ($layout->sidebarVariant === 'footer-item')
                    {{-- fi-sidebar-database-notifications-btn > .fi-icon already applies color from parent --}}
                    {{
                        \Filament\Support\generate_icon_html($layout->triggerIcon, 'language-switch::trigger', size: \Filament\Support\Enums\IconSize::Large)
                    }}
                @else
                    {{
                        \Filament\Support\generate_icon_html($layout->triggerIcon, 'language-switch::trigger', new \Illuminate\View\ComponentAttributeBag([
                            'class' => 'h-5 w-5 text-gray-500 dark:text-gray-400',
                        ]))
                    }}
                @endif
            @endif
        @endif

        {{-- Label --}}
        @if ($layout->hasLabel)
            <span
                @if ($layout->renderContext === 'sidebar')
                    x-show="$store.sidebar.isOpen"
                @endif
                @class([
                    'text-sm font-medium text-gray-700 dark:text-gray-200' => $layout->renderContext === 'topbar',
                    'fi-sidebar-item-label text-start' => $layout->sidebarVariant === 'nav-item',
                    'fi-sidebar-database-notifications-btn-label' => $layout->sidebarVariant === 'footer-item',
                ])
            >{{ $layout->currentLabel }}</span>
        @endif
    </button>
@endif

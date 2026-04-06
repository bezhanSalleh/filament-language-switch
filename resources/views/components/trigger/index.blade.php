@props(['layout'])

@if ($layout->triggerShell === 'dropdown-list-item')
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
        @php
            $isButtonShell = in_array($layout->renderContext, ['topbar', 'outside-panel'], true);
        @endphp
        {{
            $attributes->class([
                'fi-ls-trigger',
                'flex shrink-0 items-center h-9 gap-x-2 transition duration-75 outline-none' => $isButtonShell,
                'bg-gray-100 dark:bg-gray-800' => $layout->renderContext === 'topbar',
                'bg-white dark:bg-gray-900 shadow ring-1 ring-gray-950/5 dark:ring-white/10' => $layout->renderContext === 'outside-panel',
                'w-9 justify-center' => $isButtonShell && ! $layout->hasLabel,
                'px-3' => $isButtonShell && $layout->hasLabel,
                'rounded-full' => $isButtonShell && $layout->isCircular,
                'rounded-lg' => $isButtonShell && ! $layout->isCircular,
                'fi-sidebar-item-btn w-full hover:bg-gray-100 focus-visible:bg-gray-100 dark:hover:bg-white/5 dark:focus-visible:bg-white/5' => $layout->sidebarVariant === 'nav-item',
                'fi-sidebar-database-notifications-btn' => $layout->sidebarVariant === 'footer-item',
            ])
        }}
    >
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

        @if ($layout->hasLabel)
            <span
                @if ($layout->renderContext === 'sidebar')
                    x-show="$store.sidebar.isOpen"
                @endif
                @class([
                    'text-sm font-medium text-gray-700 dark:text-gray-200' => in_array($layout->renderContext, ['topbar', 'outside-panel'], true),
                    'fi-sidebar-item-label text-start' => $layout->sidebarVariant === 'nav-item',
                    'fi-sidebar-database-notifications-btn-label' => $layout->sidebarVariant === 'footer-item',
                ])
            >{{ $layout->currentLabel }}</span>
        @endif
    </button>
@endif

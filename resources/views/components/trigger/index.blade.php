@php
    $ls = \BezhanSalleh\LanguageSwitch\LanguageSwitch::make();
    $renderContext = $ls->getRenderContext();
    $triggerStyle = $ls->getTriggerStyle();
    $triggerIcon = $ls->getTriggerIcon();
    $triggerView = $ls->getTriggerView();
    $isCircular = $ls->isCircular();
    $hasFlags = filled($ls->getFlags());

    $currentLocale = app()->getLocale();
    $currentLabel = $ls->getLabel($currentLocale);
    $currentFlag = $hasFlags ? $ls->getFlag($currentLocale) : null;

    $hasLabel = str_contains($triggerStyle, '-label');
    $baseStyle = str_replace('-label', '', $triggerStyle);

    $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();
    $isVisual = in_array($baseStyle, ['flag', 'avatar']);
    $flagSrc = ($baseStyle === 'flag' && $currentFlag) ? $currentFlag : null;
    $isUrlFlag = $flagSrc && filter_var($flagSrc, FILTER_VALIDATE_URL);
@endphp

@if ($triggerView)
    @include($triggerView)
@else
    @switch($renderContext)
        @case('nav')
            {{-- Sidebar nav: <li><a> matching fi-sidebar-item --}}
            <ul class="fi-sidebar-nav-groups w-full">
                <li class="fi-sidebar-item fi-sidebar-item-has-url">
                    <a
                        href="#"
                        x-on:click.prevent
                        @if ($isSidebarCollapsibleOnDesktop)
                            x-data="{ tooltip: false }"
                            x-effect="tooltip = $store.sidebar.isOpen ? false : { content: @js($currentLabel), placement: document.dir === 'rtl' ? 'left' : 'right', theme: $store.theme }"
                            x-tooltip.html="tooltip"
                        @endif
                        {{ $attributes->class(['fi-sidebar-item-btn fi-ls-trigger w-full']) }}
                    >
                        @if ($isUrlFlag)
                            <x-filament::avatar :src="$flagSrc" :alt="$currentLabel" size="sm" :circular="$isCircular" />
                        @elseif ($baseStyle === 'avatar')
                            <span class="flex size-6 items-center justify-center font-semibold text-sm text-gray-400 dark:text-gray-500">
                                {{ str($currentLocale)->length() > 2 ? str($currentLocale)->substr(0, 2)->upper() : str($currentLocale)->upper() }}
                            </span>
                        @else
                            {{
                                \Filament\Support\generate_icon_html($triggerIcon, 'language-switch::trigger', new \Illuminate\View\ComponentAttributeBag([
                                    'class' => 'fi-sidebar-item-icon',
                                ]), size: \Filament\Support\Enums\IconSize::Large)
                            }}
                        @endif

                        @if ($hasLabel)
                            <span
                                @if ($isSidebarCollapsibleOnDesktop)
                                    x-show="$store.sidebar.isOpen"
                                    x-transition:enter="fi-transition-enter"
                                    x-transition:enter-start="fi-transition-enter-start"
                                    x-transition:enter-end="fi-transition-enter-end"
                                @endif
                                class="fi-sidebar-item-label text-start"
                            >{{ $currentLabel }}</span>
                        @endif
                    </a>
                </li>
            </ul>
            @break

        @case('user-menu')
            {{-- User menu: use Filament dropdown list item --}}
            @if ($isUrlFlag)
                <x-filament::dropdown.list.item
                    :image="$flagSrc"
                    {{ $attributes->class(['fi-ls-trigger']) }}
                >
                    {{ $currentLabel }}
                </x-filament::dropdown.list.item>
            @elseif ($baseStyle === 'avatar')
                <button {{ $attributes->class(['fi-dropdown-list-item fi-ls-trigger']) }}>
                    <span class="flex size-5 items-center justify-center shrink-0 font-semibold text-xs text-gray-400 dark:text-gray-500">
                        {{ str($currentLocale)->length() > 2 ? str($currentLocale)->substr(0, 2)->upper() : str($currentLocale)->upper() }}
                    </span>
                    <span class="fi-dropdown-list-item-label">{{ $currentLabel }}</span>
                </button>
            @else
                <x-filament::dropdown.list.item
                    :icon="$triggerIcon"
                    icon-alias="language-switch::trigger"
                    {{ $attributes->class(['fi-ls-trigger']) }}
                >
                    {{ $currentLabel }}
                </x-filament::dropdown.list.item>
            @endif
            @break

        @case('sidebar')
            @if ($hasLabel)
                {{-- Sidebar footer with label --}}
                <button
                    type="button"
                    @if ($isSidebarCollapsibleOnDesktop)
                        x-data="{ tooltip: false }"
                        x-effect="tooltip = $store.sidebar.isOpen ? false : { content: @js($currentLabel), placement: document.dir === 'rtl' ? 'left' : 'right', theme: $store.theme }"
                        x-tooltip.html="tooltip"
                    @endif
                    {{ $attributes->class(['fi-sidebar-database-notifications-btn fi-ls-trigger']) }}
                >
                    @if ($isUrlFlag)
                        <x-filament::avatar :src="$flagSrc" :alt="$currentLabel" size="sm" :circular="$isCircular" />
                    @elseif ($baseStyle === 'avatar')
                        <span class="flex size-6 items-center justify-center shrink-0 font-semibold text-sm text-gray-400 dark:text-gray-500">
                            {{ str($currentLocale)->length() > 2 ? str($currentLocale)->substr(0, 2)->upper() : str($currentLocale)->upper() }}
                        </span>
                    @else
                        {{
                            \Filament\Support\generate_icon_html($triggerIcon, 'language-switch::trigger', new \Illuminate\View\ComponentAttributeBag([
                                'class' => 'h-6 w-6 shrink-0 text-gray-400 dark:text-gray-500',
                            ]))
                        }}
                    @endif

                    <span
                        @if ($isSidebarCollapsibleOnDesktop)
                            x-show="$store.sidebar.isOpen"
                            x-transition:enter="fi-transition-enter"
                            x-transition:enter-start="fi-transition-enter-start"
                            x-transition:enter-end="fi-transition-enter-end"
                        @endif
                        class="fi-sidebar-database-notifications-btn-label"
                    >{{ $currentLabel }}</span>
                </button>
            @else
                {{-- Sidebar footer compact --}}
                @if ($isVisual)
                    <button
                        type="button"
                        aria-label="{{ $currentLabel }}"
                        x-tooltip="{ content: @js($currentLabel), theme: $store.theme }"
                        {{ $attributes->class(['fi-icon-btn fi-ls-trigger']) }}
                    >
                        @if ($isUrlFlag)
                            <x-filament::avatar :src="$flagSrc" :alt="$currentLabel" size="sm" :circular="$isCircular" />
                        @else
                            <span class="flex size-6 items-center justify-center shrink-0 font-semibold text-sm text-gray-400 dark:text-gray-500">
                                {{ str($currentLocale)->length() > 2 ? str($currentLocale)->substr(0, 2)->upper() : str($currentLocale)->upper() }}
                            </span>
                        @endif
                    </button>
                @else
                    <x-filament::icon-button
                        :icon="$triggerIcon"
                        icon-alias="language-switch::trigger"
                        :tooltip="$currentLabel"
                        :label="$currentLabel"
                        {{ $attributes->class(['fi-ls-trigger']) }}
                    />
                @endif
            @endif
            @break

        @default
            {{-- Topbar --}}
            @if ($hasLabel)
                {{-- With label: icon/avatar/flag + text --}}
                <button
                    type="button"
                    aria-label="{{ $currentLabel }}"
                    {{
                        $attributes->class([
                            'fi-icon-btn fi-ls-trigger',
                            'bg-gray-100 dark:bg-gray-800',
                            'rounded-full!' => $isCircular,
                        ])
                    }}
                >
                    @if ($isUrlFlag)
                        <x-filament::avatar :src="$flagSrc" :alt="$currentLabel" size="sm" :circular="$isCircular" />
                    @elseif ($baseStyle === 'avatar')
                        <span class="flex size-5 items-center justify-center shrink-0 font-semibold text-xs text-primary-500 dark:text-primary-400">
                            {{ str($currentLocale)->length() > 2 ? str($currentLocale)->substr(0, 2)->upper() : str($currentLocale)->upper() }}
                        </span>
                    @else
                        {{
                            \Filament\Support\generate_icon_html($triggerIcon, 'language-switch::trigger')
                        }}
                    @endif
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $currentLabel }}</span>
                </button>
            @elseif ($isVisual)
                {{-- No label, visual (flag/avatar): icon-btn with bg --}}
                <button
                    type="button"
                    aria-label="{{ $currentLabel }}"
                    x-tooltip="{ content: @js($currentLabel), theme: $store.theme }"
                    {{
                        $attributes->class([
                            'fi-icon-btn fi-ls-trigger',
                            'rounded-full!' => $isCircular,
                            'bg-gray-100 dark:bg-gray-800',
                        ])
                    }}
                    style="min-width: 36px;"
                >
                    @if ($isUrlFlag)
                        <x-filament::avatar :src="$flagSrc" :alt="$currentLabel" size="sm" :circular="$isCircular" />
                    @else
                        <span class="flex size-6 items-center justify-center shrink-0 font-semibold text-sm text-primary-500 dark:text-primary-400">
                            {{ str($currentLocale)->length() > 2 ? str($currentLocale)->substr(0, 2)->upper() : str($currentLocale)->upper() }}
                        </span>
                    @endif
                </button>
            @else
                {{-- No label, icon only: native Filament icon-button --}}
                <x-filament::icon-button
                    :icon="$triggerIcon"
                    icon-alias="language-switch::trigger"
                    :tooltip="$currentLabel"
                    :label="$currentLabel"
                    @class([
                        'fi-ls-trigger',
                        'rounded-full!' => $isCircular,
                        'bg-gray-100 dark:bg-gray-800',
                    ])
                    style="min-width: 36px;"
                    {{ $attributes }}
                />
            @endif
    @endswitch
@endif

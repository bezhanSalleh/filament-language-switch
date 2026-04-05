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
    $resolvedHook = $ls->getResolvedRenderHook();
    $isInSidebarLogo = str_contains($resolvedHook, '::sidebar.logo.');
    $isVisual = in_array($baseStyle, ['flag', 'avatar']);
    $flagSrc = ($baseStyle === 'flag' && $currentFlag) ? $currentFlag : null;
    $isUrlFlag = $flagSrc && filter_var($flagSrc, FILTER_VALIDATE_URL);
@endphp

@if ($triggerView)
    @include($triggerView)
@else
    @switch($renderContext)
        @case('user-menu')
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
            {{-- All sidebar hooks: nav, footer, start, user-menu-before/after --}}
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

                @if ($hasLabel)
                    <span
                        @if ($isSidebarCollapsibleOnDesktop)
                            x-show="$store.sidebar.isOpen"
                        @endif
                        class="fi-sidebar-database-notifications-btn-label"
                    >{{ $currentLabel }}</span>
                @endif
            </button>
            @break

        @default
            {{-- Topbar + sidebar logo: unified button design --}}
            <button
                type="button"
                aria-label="{{ $currentLabel }}"
                @unless ($hasLabel)
                    x-tooltip="{ content: @js($currentLabel), theme: $store.theme }"
                @endunless
                @if ($isInSidebarLogo && $isSidebarCollapsibleOnDesktop)
                    x-show="$store.sidebar.isOpen"
                    x-cloak
                @endif
                {{
                    $attributes->class([
                        'fi-ls-trigger flex shrink-0 items-center gap-x-2 bg-gray-100 dark:bg-gray-800 transition duration-75 outline-none',
                        'h-9 px-3' => $hasLabel,
                        'size-9 justify-center' => ! $hasLabel,
                        'rounded-full!' => $isCircular,
                        'rounded-lg' => ! $isCircular,
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
                        \Filament\Support\generate_icon_html($triggerIcon, 'language-switch::trigger', new \Illuminate\View\ComponentAttributeBag([
                            'class' => 'h-5 w-5 text-gray-500 dark:text-gray-400',
                        ]))
                    }}
                @endif

                @if ($hasLabel)
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $currentLabel }}</span>
                @endif
            </button>
    @endswitch
@endif

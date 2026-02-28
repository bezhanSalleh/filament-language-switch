@php
    $buttonStyle = $languageSwitch->getButtonStyle();
    $codeStyle = $languageSwitch->getLanguageCodeStyle();
    $isMinimalCode = $codeStyle === 'minimal';
    $displayFormat = $languageSwitch->getDisplayFormat(); // full, code, none
    $hideLanguageCodeInside = $languageSwitch->isLanguageCodeHiddenInsideModal();
    $hideLanguageCodeOutside = $languageSwitch->isLanguageCodeHiddenOutsideModal();
    $gridColumns = $languageSwitch->getGridColumns();
    $dropdownWidth = $languageSwitch->getDropdownWidth();

    $icon = $languageSwitch->getIcon();
    $iconSize = $languageSwitch->getIconSize();
    $iconPosition = $languageSwitch->getIconPosition();
    $flagSize = $languageSwitch->getFlagSize();
    $flagPosition = $languageSwitch->getFlagPosition();

    $isFlagsOnly = $languageSwitch->isFlagsOnly();
    $hasFlags = filled($languageSwitch->getFlags());
    $maxHeight = $languageSwitch->getMaxHeight();

    $finalWidth = $dropdownWidth
        ? $dropdownWidth
        : ($gridColumns > 1 ? 'lg' : ($isFlagsOnly ? 'w-fit fls-flag-only-width' : 'w-fit fls-dropdown-width'));

    $beforeCoreContent = $languageSwitch->getBeforeCoreContent();
    $beforeCoreContentClasses = $languageSwitch->getBeforeCoreContentClasses();
    $afterCoreContent = $languageSwitch->getAfterCoreContent();
    $afterCoreContentClasses = $languageSwitch->getAfterCoreContentClasses();
    $wrapperClass = $languageSwitch->getWrapperClass();

    $hasSurroundingContent = $beforeCoreContent || $afterCoreContent;
    $needsWrapper = $hasSurroundingContent || $wrapperClass;

    $isDoubleDefault = ($buttonStyle === 'default' && $codeStyle === 'default');
    $hasIcon = filled($icon);

    // Logic: It is a square/circle ONLY if:
    // 1. Forced circular OR
    // 2. No Icon AND (Flags Only OR (No Flags AND Format is Code))
    // If we have flags AND code (e.g. [Flag] EN), it is NOT a square, it is a rectangle.
    $isSquareTrigger = $languageSwitch->isCircular() || (! $hasIcon && ($isFlagsOnly || (!$hasFlags && $displayFormat === 'code')));

    $triggerClasses = \Illuminate\Support\Arr::toCssClasses([
        'flex items-center justify-center language-switch-trigger outline-none transition-colors duration-200',
        'rounded-full' => $languageSwitch->isCircular(),
        'rounded-lg' => !$languageSwitch->isCircular(),
        'w-9 h-9 aspect-square' => $isSquareTrigger,
        'gap-x-2 px-3 py-2 w-auto' => ! $isSquareTrigger,
        'text-primary-600 bg-primary-500/10 hover:bg-primary-500/20' => $buttonStyle === 'default',
        'text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800' => $buttonStyle === 'ghost',
        'border border-gray-300 bg-transparent text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800' => $buttonStyle === 'outlined',
        'bg-primary-600 text-white hover:bg-primary-500 dark:bg-primary-500 dark:hover:bg-primary-400' => $buttonStyle === 'filled',
        'bg-transparent text-gray-700 hover:bg-black/5 dark:text-gray-200 dark:hover:bg-white/5' => $buttonStyle === 'transparent',
        'ring-2 ring-inset ring-gray-200 hover:ring-gray-300 dark:ring-gray-500 hover:dark:ring-gray-400' => ($isFlagsOnly || $hasFlags) && $buttonStyle === 'default',
        $languageSwitch->getTriggerClass(),
    ]);

    $baseCodeClasses = [
        'font-semibold font-sans',
        'text-xs' => !$isMinimalCode,
        'flex items-center justify-center flex-shrink-0 aspect-square' => !$isMinimalCode,
        $flagSize => !$isMinimalCode,
        'rounded-full' => $languageSwitch->isCircular() && !$isMinimalCode,
        'rounded-lg' => !$languageSwitch->isCircular() && !$isMinimalCode,
        'text-primary-600 bg-primary-500/10' => $codeStyle === 'default' && !$isDoubleDefault,
        'text-gray-600 bg-transparent dark:text-gray-400' => $codeStyle === 'ghost',
        'border border-gray-300 bg-transparent text-gray-700 dark:border-gray-600 dark:text-gray-200' => $codeStyle === 'outlined',
        'bg-primary-600 text-white' => $codeStyle === 'filled',
        'bg-transparent text-gray-700 dark:text-gray-200' => $codeStyle === 'transparent',
        'text-gray-700 dark:text-gray-200 text-sm font-medium' => $isMinimalCode,
    ];

    $triggerCodeClasses = \Illuminate\Support\Arr::toCssClasses($baseCodeClasses);

    $listCodeClasses = \Illuminate\Support\Arr::toCssClasses(array_merge($baseCodeClasses, [
        'group-hover:border group-hover:border-primary-500/10' => $codeStyle === 'default',
        'group-hover:bg-gray-100 dark:group-hover:bg-gray-800' => $codeStyle === 'ghost',
        'group-hover:bg-primary-500' => $codeStyle === 'filled',
        'group-hover:bg-black/5 dark:group-hover:bg-white/5' => $codeStyle === 'transparent',
    ]));

    $isUserMenuItem = $languageSwitch->isRenderedAsUserMenuItem() && !$isVisibleOutsidePanels;
    $showCurrentLocale = $displayAs === 'modal' || $isUserMenuItem;
    $locales = $languageSwitch->getLocales();

    $suggestedLocales = $languageSwitch->getSuggested();
    $suggestedLocales = array_filter($suggestedLocales, fn($l) => in_array($l, $locales) && ($showCurrentLocale ? true : !app()->isLocale($l)));
    $allLocales = array_filter($locales, fn($l) => !in_array($l, $suggestedLocales) && ($showCurrentLocale ? true : !app()->isLocale($l)));

    // Columns

    $cols = $languageSwitch->getModalGridColumnsResponsive();

    // Tailwind-safe maps (strings appear literally in this file)
    $base = [
        1 => 'grid-cols-1', 2 => 'grid-cols-2', 3 => 'grid-cols-3', 4 => 'grid-cols-4',
        5 => 'grid-cols-5', 6 => 'grid-cols-6', 7 => 'grid-cols-7', 8 => 'grid-cols-8',
        9 => 'grid-cols-9', 10 => 'grid-cols-10', 11 => 'grid-cols-11', 12 => 'grid-cols-12',
    ];

    $bp = [
        'sm' => [
            1 => 'sm:grid-cols-1', 2 => 'sm:grid-cols-2', 3 => 'sm:grid-cols-3', 4 => 'sm:grid-cols-4',
            5 => 'sm:grid-cols-5', 6 => 'sm:grid-cols-6', 7 => 'sm:grid-cols-7', 8 => 'sm:grid-cols-8',
            9 => 'sm:grid-cols-9', 10 => 'sm:grid-cols-10', 11 => 'sm:grid-cols-11', 12 => 'sm:grid-cols-12',
        ],
        'md' => [
            1 => 'md:grid-cols-1', 2 => 'md:grid-cols-2', 3 => 'md:grid-cols-3', 4 => 'md:grid-cols-4',
            5 => 'md:grid-cols-5', 6 => 'md:grid-cols-6', 7 => 'md:grid-cols-7', 8 => 'md:grid-cols-8',
            9 => 'md:grid-cols-9', 10 => 'md:grid-cols-10', 11 => 'md:grid-cols-11', 12 => 'md:grid-cols-12',
        ],
        'lg' => [
            1 => 'lg:grid-cols-1', 2 => 'lg:grid-cols-2', 3 => 'lg:grid-cols-3', 4 => 'lg:grid-cols-4',
            5 => 'lg:grid-cols-5', 6 => 'lg:grid-cols-6', 7 => 'lg:grid-cols-7', 8 => 'lg:grid-cols-8',
            9 => 'lg:grid-cols-9', 10 => 'lg:grid-cols-10', 11 => 'lg:grid-cols-11', 12 => 'lg:grid-cols-12',
        ],
        'xl' => [
            1 => 'xl:grid-cols-1', 2 => 'xl:grid-cols-2', 3 => 'xl:grid-cols-3', 4 => 'xl:grid-cols-4',
            5 => 'xl:grid-cols-5', 6 => 'xl:grid-cols-6', 7 => 'xl:grid-cols-7', 8 => 'xl:grid-cols-8',
            9 => 'xl:grid-cols-9', 10 => 'xl:grid-cols-10', 11 => 'xl:grid-cols-11', 12 => 'xl:grid-cols-12',
        ],
        '2xl' => [
            1 => '2xl:grid-cols-1', 2 => '2xl:grid-cols-2', 3 => '2xl:grid-cols-3', 4 => '2xl:grid-cols-4',
            5 => '2xl:grid-cols-5', 6 => '2xl:grid-cols-6', 7 => '2xl:grid-cols-7', 8 => '2xl:grid-cols-8',
            9 => '2xl:grid-cols-9', 10 => '2xl:grid-cols-10', 11 => '2xl:grid-cols-11', 12 => '2xl:grid-cols-12',
        ],
    ];

    $gridClasses = 'grid gap-4 ' . ($base[$cols['default'] ?? 1] ?? 'grid-cols-1');

    foreach (['sm', 'md', 'lg', 'xl', '2xl'] as $k) {
        if (isset($cols[$k]) && isset($bp[$k][$cols[$k]])) {
            $gridClasses .= ' ' . $bp[$k][$cols[$k]];
        }
    }
@endphp




@if (! $isUserMenuItem)
    @if ($needsWrapper) <div class="flex items-center gap-3 {{ $wrapperClass }}"> @endif

        @if ($beforeCoreContent)
            <div @if($beforeCoreContentClasses) class="{{ $beforeCoreContentClasses }}" @endif>
                {!! $beforeCoreContent !!}
            </div>
        @endif

        @php
            $triggerContent = function() use ($icon, $iconSize, $iconPosition, $isFlagsOnly, $hasFlags, $languageSwitch, $flagSize, $flagPosition, $hideLanguageCodeOutside, $triggerCodeClasses, $displayFormat) {
                $isCircular = $languageSwitch->isCircular();
                $output = '';

                // 1. Icon (Before)
                if ($icon && $iconPosition === 'before') {
                    $output .= \Illuminate\Support\Facades\Blade::render('<x-filament::icon :icon="$icon" :class="$size . \' text-inherit flex-shrink-0\'" />',['icon' => $icon, 'size' => $iconSize]);
                }

                // 2. Flag OR Code-Avatar (Before)
                if ($flagPosition === 'before') {
                    if ($isFlagsOnly || $hasFlags) {
                        // CASE A: We have flags -> Show Flag Image
                        $output .= \Illuminate\Support\Facades\Blade::render('<x-language-switch::flag :src="$src" :circular="$circular" :alt="$alt" :switch="true" :class="$size . \' aspect-square object-cover\'" />',['src' => $languageSwitch->getFlag(app()->getLocale()), 'circular' => $isCircular, 'alt' => $languageSwitch->getLabel(app()->getLocale()), 'size' => $flagSize]);
                    } elseif (! $hideLanguageCodeOutside && $displayFormat === 'code') {
                        // CASE B: No flags -> Show "EN" inside the styled box (Avatar style)
                        $output .= '<span class="'.$triggerCodeClasses.'">'.$languageSwitch->getCharAvatar(app()->getLocale()).'</span>';
                    }
                }

                // 3. Text Label (Middle)
                if (! $isFlagsOnly) {
                    if ($displayFormat === 'full') {
                        // Full Name (e.g., "English")
                        $output .= '<span class="font-medium text-sm font-sans">'.$languageSwitch->getLabel(app()->getLocale()).'</span>';
                    } elseif ($displayFormat === 'code' && $hasFlags) {
                        // Code Name (e.g., "EN")
                        // -> ONLY show this text if we have flags.
                        // -> If we don't have flags, the code is already shown in Step 2 as the main button style.
                        $output .= '<span class="font-medium text-sm font-sans">'.$languageSwitch->getCharAvatar(app()->getLocale()).'</span>';
                    }
                }

                // 4. Flag/Code (After)
                if ($flagPosition === 'after') {
                    if ($isFlagsOnly || $hasFlags) {
                        $output .= \Illuminate\Support\Facades\Blade::render('<x-language-switch::flag :src="$src" :circular="$circular" :alt="$alt" :switch="true" :class="$size . \' aspect-square object-cover\'" />',['src' => $languageSwitch->getFlag(app()->getLocale()), 'circular' => $isCircular, 'alt' => $languageSwitch->getLabel(app()->getLocale()), 'size' => $flagSize]);
                    } elseif (! $hideLanguageCodeOutside && $displayFormat === 'code') {
                         // CASE B (After): No flags -> Show "EN" inside the styled box
                        $output .= '<span class="'.$triggerCodeClasses.'">'.$languageSwitch->getCharAvatar(app()->getLocale()).'</span>';
                    }
                }

                // 5. Icon (After)
                if ($icon && $iconPosition === 'after') {
                    $output .= \Illuminate\Support\Facades\Blade::render('<x-filament::icon :icon="$icon" :class="$size . \' text-inherit flex-shrink-0\'" />', ['icon' => $icon, 'size' => $iconSize]);
                }

                return $output;
            };
        @endphp

        @if ($displayAs === 'modal')
            <button type="button" class="{{ $triggerClasses }}" x-on:click="$dispatch('open-modal', { id: 'fls-modal' })">
                {!! $triggerContent() !!}
            </button>
        @else
            <x-filament::dropdown teleport :placement="$placement" :width="$finalWidth" :max-height="$maxHeight" class="fi-dropdown fi-user-menu" data-nosnippet="true">
                <x-slot name="trigger">
                    <button type="button" class="{{ $triggerClasses }}">
                        {!! $triggerContent() !!}
                    </button>
                </x-slot>
                <x-filament::dropdown.list :class="$gridColumns > 1 ? 'grid gap-2 !p-3' : '!border-t-0 space-y-1 !p-2.5'" :style="$gridColumns > 1 ? 'grid-template-columns: repeat(' . $gridColumns . ', minmax(0, 1fr));' : ''">
                    @if (count($suggestedLocales) > 0)
                        <div class="col-span-full px-2 py-1 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">{{ __('language-switch::translations.suggested') }}</div>
                        @foreach ($suggestedLocales as $locale)
                            @include('language-switch::list-item', ['locale' => $locale, 'hideLanguageCode' => $hideLanguageCodeInside, 'codeClasses' => $listCodeClasses, 'itemStyle' => 'list'])
                        @endforeach
                        @if (count($allLocales) > 0) <div class="col-span-full my-1 border-t border-gray-200 dark:border-gray-700"></div> @endif
                    @endif
                    @foreach ($allLocales as $locale)
                        @include('language-switch::list-item', ['locale' => $locale, 'hideLanguageCode' => $hideLanguageCodeInside, 'codeClasses' => $listCodeClasses, 'itemStyle' => 'list'])
                    @endforeach
                </x-filament::dropdown.list>
            </x-filament::dropdown>
        @endif

        @if ($afterCoreContent)
            <div @if($afterCoreContentClasses) class="{{ $afterCoreContentClasses }}" @endif>
                {!! $afterCoreContent !!}
            </div>
        @endif

        @if ($needsWrapper) </div> @endif
@endif

@if ($displayAs === 'modal' || $isUserMenuItem)
    <x-filament::modal id="fls-modal" class="{{ $languageSwitch->getModalClass() }}" :heading="$languageSwitch->getModalHeading()" :description="$languageSwitch->getModalDescription()" :width="$languageSwitch->getModalWidth() ?? 'md'" :slide-over="$languageSwitch->isModalSlideOver()" :sticky-header="$languageSwitch->isModalSlideOver()" :alignment="$languageSwitch->getModalAlignment()" :close-button="$languageSwitch->hasModalCloseButton() ?? true" :autofocus="$languageSwitch->isModalAutofocused() ?? true" :icon="$languageSwitch->getModalIcon()" :icon-color="$languageSwitch->getModalIconColor()" :close-by-clicking-away="$languageSwitch->isModalClosedByClickingAway() ?? true" :close-by-escaping="$languageSwitch->isModalClosedByEscaping() ?? true">
            <div class="{{ $gridClasses }}">
            @if (count($suggestedLocales) > 0)
                <div class="col-span-full px-2 py-1 text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400">{{ __('language-switch::translations.suggested') }}</div>
                @foreach ($suggestedLocales as $locale)
                    @include('language-switch::list-item', ['locale' => $locale, 'hideLanguageCode' => $hideLanguageCodeInside, 'codeClasses' => $listCodeClasses, 'itemStyle' => $languageSwitch->getItemStyle()])
                @endforeach
                @if (count($allLocales) > 0) <div class="col-span-full my-1 border-t border-gray-200 dark:border-gray-700"></div> @endif
            @endif
            @foreach ($allLocales as $locale)
                @include('language-switch::list-item', ['locale' => $locale, 'hideLanguageCode' => $hideLanguageCodeInside, 'codeClasses' => $listCodeClasses, 'itemStyle' => $languageSwitch->getItemStyle()])
            @endforeach
        </div>
    </x-filament::modal>
    @if ($isUserMenuItem)
        <script>
            document.addEventListener('alpine:init', () => {
                document.body.addEventListener('click', (e) => {
                    let link = e.target.closest('a[href$="#fls-modal"]');
                    if (link) { e.preventDefault(); window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'fls-modal' } })); }
                });
            });
        </script>
    @endif
@endif

@php
    $buttonStyle = $languageSwitch->getButtonStyle();
    $codeStyle = $languageSwitch->getLanguageCodeStyle();
    $isMinimalCode = $codeStyle === 'minimal';
    $displayFullLabel = $languageSwitch->isDisplayFullLabel();
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
    $afterCoreContent = $languageSwitch->getAfterCoreContent();
    $hasSurroundingContent = $beforeCoreContent || $afterCoreContent;

    $isDoubleDefault = ($buttonStyle === 'default' && $codeStyle === 'default');
    $hasIcon = filled($icon);
    $isSquareTrigger = $languageSwitch->isCircular() || (! $hasIcon && ($isFlagsOnly || ! $displayFullLabel));

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
@endphp

@if (! $isUserMenuItem)
    @if ($hasSurroundingContent) <div class="flex items-center gap-3"> @endif
        {{!! $beforeCoreContent !!}}

        @php
            $triggerContent = function() use ($icon, $iconSize, $iconPosition, $isFlagsOnly, $hasFlags, $languageSwitch, $flagSize, $flagPosition, $hideLanguageCodeOutside, $triggerCodeClasses, $displayFullLabel) {
                $isCircular = $languageSwitch->isCircular();
                $output = '';

                // 1. Icon (Before)
                if ($icon && $iconPosition === 'before') $output .= Blade::render('<x-filament::icon :icon="$icon" :class="$size . \' text-inherit flex-shrink-0\'" />', ['icon' => $icon, 'size' => $iconSize]);

                // 2. Flag/Code (Before)
                if ($flagPosition === 'before') {
                    if ($isFlagsOnly || $hasFlags) $output .= Blade::render('<x-language-switch::flag :src="$src" :circular="$circular" :alt="$alt" :switch="true" :class="$size . \' aspect-square object-cover\'" />', ['src' => $languageSwitch->getFlag(app()->getLocale()), 'circular' => $isCircular, 'alt' => $languageSwitch->getLabel(app()->getLocale()), 'size' => $flagSize]);
                    elseif (! $hideLanguageCodeOutside) $output .= '<span class="'.$triggerCodeClasses.'">'.$languageSwitch->getCharAvatar(app()->getLocale()).'</span>';
                }

                // 3. Label
                if ($displayFullLabel && ! $isFlagsOnly) $output .= '<span class="font-medium text-sm font-sans">'.$languageSwitch->getLabel(app()->getLocale()).'</span>';

                // 4. Flag/Code (After)
                if ($flagPosition === 'after') {
                    if ($isFlagsOnly || $hasFlags) $output .= Blade::render('<x-language-switch::flag :src="$src" :circular="$circular" :alt="$alt" :switch="true" :class="$size . \' aspect-square object-cover\'" />', ['src' => $languageSwitch->getFlag(app()->getLocale()), 'circular' => $isCircular, 'alt' => $languageSwitch->getLabel(app()->getLocale()), 'size' => $flagSize]);
                    elseif (! $hideLanguageCodeOutside) $output .= '<span class="'.$triggerCodeClasses.'">'.$languageSwitch->getCharAvatar(app()->getLocale()).'</span>';
                }

                // 5. Icon (After)
                if ($icon && $iconPosition === 'after') $output .= Blade::render('<x-filament::icon :icon="$icon" :class="$size . \' text-inherit flex-shrink-0\'" />', ['icon' => $icon, 'size' => $iconSize]);

                return $output;
            };
        @endphp

        @if ($displayAs === 'modal')
            <button type="button" class="{{ $triggerClasses }}" x-on:click="$dispatch('open-modal', { id: 'fls-modal' })" x-tooltip="{ content: @js($languageSwitch->getLabel(app()->getLocale())), theme: $store.theme, placement: 'right' }">
                {!! $triggerContent() !!}
            </button>
        @else
            <x-filament::dropdown teleport :placement="$placement" :width="$finalWidth" :max-height="$maxHeight" class="fi-dropdown fi-user-menu" data-nosnippet="true">
                <x-slot name="trigger">
                    <button type="button" class="{{ $triggerClasses }}" x-tooltip="{ content: @js($languageSwitch->getLabel(app()->getLocale())), theme: $store.theme, placement: 'right' }">
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
        {{!!  $afterCoreContent !!}}
        @if ($hasSurroundingContent) </div> @endif
@endif

@if ($displayAs === 'modal' || $isUserMenuItem)
    <x-filament::modal id="fls-modal" class="{{ $languageSwitch->getModalClass() }}" :heading="$languageSwitch->getModalHeading()" :description="$languageSwitch->getModalDescription()" :width="$languageSwitch->getModalWidth() ?? 'md'" :slide-over="$languageSwitch->isModalSlideOver()" :sticky-header="$languageSwitch->isModalSlideOver()" :alignment="$languageSwitch->getModalAlignment()" :close-button="$languageSwitch->hasModalCloseButton() ?? true" :autofocus="$languageSwitch->isModalAutofocused() ?? true" :icon="$languageSwitch->getModalIcon()" :icon-color="$languageSwitch->getModalIconColor()" :close-by-clicking-away="$languageSwitch->isModalClosedByClickingAway() ?? true" :close-by-escaping="$languageSwitch->isModalClosedByEscaping() ?? true">
        <div class="grid gap-4" style="grid-template-columns: repeat({{ $languageSwitch->getModalGridColumns() }}, minmax(0, 1fr));">
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

@php
    $isCurrent = app()->isLocale($locale);
    $customClass = $languageSwitch->getItemClass();
    $hasFlags = filled($languageSwitch->getFlags());
    $isFlagsOnly = $languageSwitch->isFlagsOnly();
    $isCircular = $languageSwitch->isCircular();
@endphp

@if ($itemStyle === 'card')
    <button type="button" @if(! $isCurrent) wire:click="changeLocale('{{ $locale }}')" @endif x-on:click="$el.blur()" @class([
            'relative flex items-center justify-center w-full p-3 transition-colors rounded-lg border',
            'border-primary-600 bg-primary-500/10 text-primary-600 dark:border-primary-500 dark:text-primary-500' => $isCurrent,
            'border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50 dark:border-white/10 dark:bg-white/5 dark:text-gray-200 dark:hover:border-white/20 dark:hover:bg-white/10' => ! $isCurrent,
            $customClass,
        ])>
        @if ($isCurrent)
            <svg class="absolute top-2 right-2 w-4 h-4 text-primary-600 dark:text-primary-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
            </svg>
        @endif
        <div class="flex items-center gap-3">
            @if ($isFlagsOnly || $hasFlags)
                <x-language-switch::flag :src="$languageSwitch->getFlag($locale)" :circular="$isCircular" :alt="$languageSwitch->getLabel($locale)" class="w-7 h-7 aspect-square object-cover" />
            @elseif (! $hideLanguageCode)
                <span class="font-semibold text-sm">{{ $languageSwitch->getCharAvatar($locale) }}</span>
            @endif
            @if (! $isFlagsOnly) <span class="text-sm font-medium font-sans">{{ $languageSwitch->getLabel($locale) }}</span> @endif
        </div>
    </button>
@else
    <button type="button" @if(! $isCurrent) wire:click="changeLocale('{{ $locale }}')" @endif x-on:click="$el.blur()" @if ($isFlagsOnly) x-tooltip="{ content: @js($languageSwitch->getLabel($locale)), theme: $store.theme, placement: 'right' }" @endif
        @class([
            'group flex items-center w-full transition-colors duration-75 rounded-md outline-none fi-dropdown-list-item whitespace-nowrap disabled:pointer-events-none disabled:opacity-70 p-2',
            'bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-500' => $isCurrent,
            'text-gray-700 hover:bg-gray-50 focus:bg-gray-50 dark:text-gray-200 dark:hover:bg-white/5 dark:focus:bg-white/5' => ! $isCurrent,
            'justify-center' => $isFlagsOnly,
            'justify-start space-x-2 rtl:space-x-reverse' => !$isFlagsOnly,
            $customClass,
        ])>
        @if ($isFlagsOnly || $hasFlags)
            <x-language-switch::flag :src="$languageSwitch->getFlag($locale)" :circular="$isCircular" :alt="$languageSwitch->getLabel($locale)" class="w-7 h-7 aspect-square object-cover" />
        @elseif (! $hideLanguageCode)
            <span @class([$codeClasses, '!bg-transparent !text-inherit' => $isCurrent])>{{ $languageSwitch->getCharAvatar($locale) }}</span>
        @endif
        @if (! $isFlagsOnly) <span class="text-sm font-medium font-sans">{{ $languageSwitch->getLabel($locale) }}</span> @endif
    </button>
@endif

{{-- <div x-data="{
    isLocaleDropdownOpen: false,
    direction: @js(__('filament::layout.direction') ?? 'ltr'),
    isRTL() {
        if (this.direction === 'rtl') {
            return true
        }
        return false
    }
}" class="relative"
    :style="(isRTL() && { margin: '0 1rem 0 0' }) || (!isRTL() && { margin: '0 0 0 1rem' })">

    <button x-on:click="isLocaleDropdownOpen = ! isLocaleDropdownOpen"
        class="flex items-center justify-center w-10 h-10 border border-gray-300 rounded-full shrink-0 text-primary-500 filament-language-switch-button hover:bg-gray-500/5 focus:bg-primary-500/10 focus:outline-none lg:mr-2 rtl:lg:mr-0 rtl:lg:ml-2">
        @if (config('filament-language-switch.flag'))
            <x-dynamic-component :component="'flag-1x1-' .
                (!blank(config('filament-language-switch.locales.' . app()->getLocale() . '.flag_code'))
                    ? config('filament-language-switch.locales.' . app()->getLocale() . '.flag_code')
                    : 'un')"
                class="rounded-full w-9 h-9 group-hover:text-white group-focus:text-white text-primary-500" />
        @else
            <span
                class="flex items-center justify-center font-semibold text-white rounded-full w-9 h-9 group-hover:text-white group-focus:text-white bg-primary-500">
                {{ str(app()->getLocale())->length() > 2
                    ? str(app()->getLocale())->substr(0, 2)->upper()
                    : str(app()->getLocale())->upper() }}
            </span>
        @endif
    </button>

    <div x-show="isLocaleDropdownOpen" x-on:click.away="isLocaleDropdownOpen = false" x-transition:enter="transition"
        x-transition:enter-start="-translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition" x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-1 opacity-0"
        class="absolute right-0 z-10 mt-2 shadow-xl rtl:right-auto rtl:left-0 rounded-xl w-52 top-full" x-cloak>
        <ul @class([
            'py-1 space-y-1 overflow-hidden bg-white shadow rounded-xl',
            'dark:border-gray-600 dark:bg-gray-700' => config('filament.dark_mode'),
        ])>
            @foreach (config('filament-language-switch.locales') as $key => $locale)
                <li @class([
                    'flex items-center justify-between group px-2',
                    'focus:outline-none hover:text-primary-600 focus:text-primary-600  cursor-pointer' => !app()->isLocale(
                        $key
                    ),
                ])>
                    <a wire:click="changeLocale('{{ $key }}')"
                        class="flex items-center w-full h-8 px-2 text-sm font-medium whitespace-nowrap filament-dropdown-item">
                        @if (config('filament-language-switch.flag'))
                            <x-dynamic-component :component="'flag-1x1-' . (!blank($locale['flag_code']) ? $locale['flag_code'] : 'un')"
                                class="flex-shrink-0 w-5 h-5 mr-2 -ml-1 rtl:ml-2 rtl:-mr-1 group-hover:text-white group-focus:text-white text-primary-500"
                                style="border-radius: 0.25rem" />
                        @else
                            <span
                                class="w-6 h-6 flex items-center justify-center mr-2 -ml-1 flex-shrink-0 rtl:ml-2 rtl:-mr-1 @if (!app()->isLocale($key)) group-hover:bg-white group-hover:text-primary-600 group-hover:border group-hover:border-primary-500/10 group-focus:text-white @endif bg-primary-500/10 text-primary-600 font-semibold rounded-full p-1 text-xs">
                                {{ str($locale['name'])->snake()->upper()->explode('_')->map(function ($string) use ($locale) {
                                        return str($locale['name'])->wordCount() > 1 ? str()->substr($string, 0, 1) : str()->substr($string, 0, 2);
                                    })->take(2)->implode('') }}
                            </span>
                        @endif
                        <span class="truncate">
                            {{ str($locale[config('filament-language-switch.native') ? 'native' : 'name'])->headline() }}
                        </span>

                    </a>
                    @if (app()->isLocale($key))
                        <x-dynamic-component component="heroicon-s-check"
                            class="flex-shrink-0 w-4 h-4 mr-2 rtl:ml-2 text-primary-500" />
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div> --}}
<x-filament::dropdown placement="bottom-end">
    <style>
        .filament-dropdown-list-item-label {
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
    </style>
    <x-slot name="trigger" @class([
        'ml-4' => __('filament::layout.direction') === 'ltr',
        'mr-4' => __('filament::layout.direction') === 'rtl',
    ])>
        <div
            class="w-10 h-10 rounded-full bg-primary-500  dark:bg-gray-900 text-white font-semibold bg-cover bg-center flex items-center justify-center">
            {{ str(app()->getLocale())->length() > 2
                ? str(app()->getLocale())->substr(0, 2)->upper()
                : str(app()->getLocale())->upper() }}
        </div>
    </x-slot>
    <x-filament::dropdown.list class="">
        @foreach (config('filament-language-switch.locales') as $key => $locale)
            @if (!app()->isLocale($key))
                <x-filament::dropdown.list.item wire:click="changeLocale('{{ $key }}')" tag="button">

                    @if (config('filament-language-switch.flag'))
                        <span>
                            <x-dynamic-component :component="'flag-1x1-' . (!blank($locale['flag_code']) ? $locale['flag_code'] : 'un')"
                                class="flex-shrink-0 w-5 h-5 mr-4 rtl:ml-4 group-hover:text-white group-focus:text-white text-primary-500"
                                style="border-radius: 0.25rem" />
                        </span>
                    @else
                        <span
                            class="w-6 h-6 flex items-center justify-center mr-4 flex-shrink-0 rtl:ml-4 @if (!app()->isLocale($key)) group-hover:bg-white group-hover:text-primary-600 group-hover:border group-hover:border-primary-500/10 group-focus:text-white @endif bg-primary-500/10 text-primary-600 font-semibold rounded-full p-1 text-xs">
                            {{ str($locale['name'])->snake()->upper()->explode('_')->map(function ($string) use ($locale) {
                                    return str($locale['name'])->wordCount() > 1 ? str()->substr($string, 0, 1) : str()->substr($string, 0, 2);
                                })->take(2)->implode('') }}
                        </span>
                    @endif
                    <span class="hover:bg-transparent">
                        {{ str($locale[config('filament-language-switch.native') ? 'native' : 'name'])->headline() }}
                    </span>
                </x-filament::dropdown.list.item>
            @endif
        @endforeach

    </x-filament::dropdown.list>
</x-filament::dropdown>

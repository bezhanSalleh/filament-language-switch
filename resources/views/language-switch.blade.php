<div x-data="{
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
            class="shrink-0 flex items-center justify-center w-10 h-10 text-primary-500 rounded-full filament-language-switch-button hover:bg-gray-500/5 focus:bg-primary-500/10 focus:outline-none lg:mr-2 rtl:lg:mr-0 rtl:lg:ml-2 border border-gray-300">
            @if (config('filament-language-switch.flag'))
                <x-dynamic-component :component="'flag-1x1-' .
                    (!blank(config('filament-language-switch.locales.' . app()->getLocale() . '.flag_code'))
                        ? config('filament-language-switch.locales.' . app()->getLocale() . '.flag_code')
                        : 'un')"
                    class="w-9 h-9 rounded-full group-hover:text-white group-focus:text-white text-primary-500" />
            @else
                <span
                    class="flex items-center justify-center w-9 h-9 rounded-full group-hover:text-white group-focus:text-white bg-primary-500 text-white font-semibold">
                    {{
                    str(app()->getLocale())->length() > 2 ? str(app()->getLocale())->substr(0,2)->upper() :
                    str(app()->getLocale())->upper() }}
                </span>
            @endif
        </button>

        <div x-show="isLocaleDropdownOpen" x-on:click.away="isLocaleDropdownOpen = false" x-transition:enter="transition"
            x-transition:enter-start="-translate-y-1 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
            x-transition:leave="transition" x-transition:leave-start="translate-y-0 opacity-100"
            x-transition:leave-end="-translate-y-1 opacity-0"
            class="absolute z-10 right-0 rtl:right-auto rtl:left-0 mt-2 shadow-xl rounded-xl w-52 top-full" x-cloak>
            <ul class="py-1 space-y-1 overflow-hidden bg-white shadow rounded-xl dark:border-gray-600 dark:bg-gray-700">

                @foreach (config('filament-language-switch.locales') as $key => $locale)
                    <li @class([
                        'flex items-center justify-between group px-2',
                        'focus:outline-none hover:text-white focus:text-white hover:bg-primary-600 focus:bg-primary-700 cursor-pointer' => !app()->isLocale(
                            $key
                        ),
                    ])>
                        <a wire:click="changeLocale('{{ $key }}')"
                            class="flex items-center w-full h-8 px-2 text-sm font-medium whitespace-nowrap filament-dropdown-item">
                            @if (config('filament-language-switch.flag'))
                                <x-dynamic-component :component="'flag-1x1-' . (!blank($locale['flag_code']) ? $locale['flag_code'] : 'un')"
                                    class="mr-2 -ml-1 w-5 h-5 flex-shrink-0 rtl:ml-2 rtl:-mr-1 group-hover:text-white group-focus:text-white text-primary-500"
                                    style="border-radius: 0.25rem" />
                            @else
                                <span
                                    class="w-6 h-6 flex items-center justify-center mr-2 -ml-1 flex-shrink-0 rtl:ml-2 rtl:-mr-1 @if (!app()->isLocale($key)) group-hover:bg-gray-100 group-hover:text-white group-hover:border group-hover:border-primary-500/10 group-focus:text-white @endif bg-primary-500/10 text-primary-600 font-semibold rounded-full p-1 text-xs">
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
                                class="w-4 h-4 flex-shrink-0 mr-2 rtl:ml-2 text-primary-500" />
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

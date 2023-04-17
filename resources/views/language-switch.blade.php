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
            class="flex items-center justify-center w-10 h-10 font-semibold text-white bg-center bg-cover rounded-full language-switch-trigger bg-primary-500 dark:bg-gray-900">
            {{ \Illuminate\Support\Str::of(app()->getLocale())->length() > 2
                ? \Illuminate\Support\Str::of(app()->getLocale())->substr(0, 2)->upper()
                : \Illuminate\Support\Str::of(app()->getLocale())->upper() }}
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
                            {{ \Illuminate\Support\Str::of($locale['name'])->snake()->upper()->explode('_')->map(function ($string) use ($locale) {
                                    return \Illuminate\Support\Str::of($locale['name'])->wordCount() > 1 ? \Illuminate\Support\Str::substr($string, 0, 1) : \Illuminate\Support\Str::substr($string, 0, 2);
                                })->take(2)->implode('') }}
                        </span>
                    @endif
                    <span class="hover:bg-transparent">
                        {{ \Illuminate\Support\Str::of($locale[config('filament-language-switch.native') ? 'native' : 'name'])->headline() }}
                    </span>
                </x-filament::dropdown.list.item>
            @endif
        @endforeach

    </x-filament::dropdown.list>
</x-filament::dropdown>

@props([
    'locale',
    'label',
    'flag' => null,
    'charAvatar' => null,
    'isFlagsOnly' => false,
    'isCircular' => false,
])

@if ($flag)
    {{-- Flag items: use Filament's dropdown.list.item with image prop --}}
    {{-- image prop renders as <div> with size-5 rounded-full bg-cover bg-center --}}
    <x-filament::dropdown.list.item
        :image="$flag"
        wire:click="changeLocale('{{ $locale }}')"
        :tooltip="$isFlagsOnly ? $label : null"
    >
        @unless ($isFlagsOnly)
            {{ $label }}
        @endunless
    </x-filament::dropdown.list.item>
@else
    {{-- Char avatar items: custom button (Filament component has no prefix slot for text) --}}
    <button
        type="button"
        wire:click="changeLocale('{{ $locale }}')"
        @if ($isFlagsOnly)
            x-tooltip="{
                content: @js($label),
                theme: $store.theme,
                placement: 'right',
            }"
        @endif
        @class([
            'fi-dropdown-list-item whitespace-nowrap',
            'justify-center px-1.5 py-1' => $isFlagsOnly,
        ])
    >
        <x-language-switch::char-avatar
            :locale="$locale"
            @class([
                'size-5',
                'rounded-full' => $isCircular,
            ])
        />

        @unless ($isFlagsOnly)
            <span class="fi-dropdown-list-item-label">{{ $label }}</span>
        @endunless
    </button>
@endif

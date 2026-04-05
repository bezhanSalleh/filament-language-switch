@php
    $labelClass = 'text-xs text-gray-400';
    $sectionClass = 'text-[10px] font-semibold uppercase tracking-widest text-primary-400';
    $isModal = $displayMode === 'modal';
    $wrapperClass = '[&_.fi-input-wrp]:h-8! [&_.fi-input-wrp]:min-h-0!';
    $selectClass = 'text-xs! py-1!';
    $inputFieldClass = 'text-xs! py-1!';
    // LTR-only overrides with !important. The debug panel forces dir="ltr" on its root,
    // so only LTR knob positions apply. rtl: variants are intentionally omitted because
    // Tailwind's rtl: selector matches via [dir=rtl] * (any ancestor), which breaks the
    // dir=ltr override inside an RTL app. Filament's own rtl: base rules have no !important
    // so our LTR overrides win in all directions.
    $toggleClass = 'items-center h-5 w-9 [&>:first-child]:size-3.5! [&.fi-toggle-on>:first-child]:translate-x-4! [&.fi-toggle-off>:first-child]:translate-x-0.5!';
@endphp

<div
    x-data="{ open: @entangle('isOpen') }"
    class="fixed bottom-4 end-4 z-50"
>
    <button
        type="button"
        x-on:click="open = ! open"
        x-tooltip="{ content: 'Language Switch Debug', theme: $store.theme }"
        class="flex size-10 items-center justify-center rounded-full bg-primary-950 text-gray-300 shadow ring-1 ring-primary-500/30 transition hover:text-white hover:ring-primary-500/60"
    >
        {{
            \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::Language, attributes: new \Illuminate\View\ComponentAttributeBag([
                'class' => 'size-5',
            ]))
        }}
    </button>

    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        x-anchor.bottom-end.offset.8="$el.previousElementSibling"
        dir="ltr"
        class="w-80 md:w-4xl max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl bg-black shadow-2xl ring-1 ring-white/5"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-2.5">
            <span class="text-xs font-semibold text-warning-400">Language Switch</span>
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-warning-400/10 px-1.5 py-px text-[9px] font-bold tracking-wide text-warning-400">DEBUG</span>
                <button type="button" wire:click="resetDebug" class="rounded px-1.5 py-0.5 text-[10px] font-medium text-gray-500 transition hover:bg-white/6 hover:text-gray-300">Reset</button>
            </div>
        </div>

        <div class="h-px bg-white/6"></div>

        <div class="max-h-[60vh] overflow-y-auto md:max-h-none md:overflow-visible grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-white/6">

            {{-- Column 1: Trigger --}}
            <div class="space-y-3 p-4">
                <h4 class="{{ $sectionClass }}">Trigger</h4>

                <div class="flex items-center justify-between">
                    <span class="{{ $labelClass }}">Topbar</span>
                    <x-filament::toggle class="{{ $toggleClass }}" :state="$topbar ? 'true' : 'false'" wire:click="$toggle('topbar')" />
                </div>

                <div class="space-y-1 {{ $wrapperClass }}">
                    <span class="{{ $labelClass }}">Style</span>
                    <x-filament::input.wrapper>
                        <x-filament::input.select class="{{ $selectClass }}" wire:model.live="triggerStyle">
                            <option value="">Auto</option>
                            <option value="icon">Icon</option>
                            <option value="icon-label">Icon + Label</option>
                            <option value="avatar">Avatar</option>
                            <option value="avatar-label">Avatar + Label</option>
                            <option value="flag" @disabled(! $useFlags)>Flag</option>
                            <option value="flag-label" @disabled(! $useFlags)>Flag + Label</option>
                            <option value="label">Label Only</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-1 {{ $wrapperClass }}">
                    <span class="{{ $labelClass }}">Icon</span>
                    <x-filament::input.wrapper>
                        <x-filament::input class="{{ $inputFieldClass }}" type="text" wire:model="triggerIcon" x-on:blur="$wire.applyIcon()" x-on:keydown.enter.prevent="$wire.applyIcon()" placeholder="Default" />
                    </x-filament::input.wrapper>
                </div>

                <div class="space-y-1 {{ $wrapperClass }}">
                    <span class="{{ $labelClass }}">Render Hook</span>
                    <x-filament::input.wrapper>
                        <x-filament::input.select class="{{ $selectClass }}" wire:model.live="renderHook">
                            <option value="">Auto (default)</option>
                            @if ($hasTopbar)
                                <optgroup label="Topbar">
                                    <option value="panels::global-search.after">Global Search After</option>
                                    <option value="panels::global-search.before">Global Search Before</option>
                                    <option value="panels::topbar.start">Topbar Start</option>
                                    <option value="panels::topbar.end">Topbar End</option>
                                </optgroup>
                            @endif
                            <optgroup label="Sidebar">
                                <option value="panels::sidebar.logo.before">Logo Before</option>
                                <option value="panels::sidebar.logo.after">Logo After</option>
                                <option value="panels::sidebar.nav.start">Nav Start</option>
                                <option value="panels::sidebar.nav.end">Nav End</option>
                                <option value="panels::sidebar.start">Header</option>
                                <option value="panels::sidebar.footer">Footer</option>
                            </optgroup>
                            <optgroup label="User Menu">
                                <option value="panels::user-menu.before">Before</option>
                                <option value="panels::user-menu.after">After</option>
                                <option value="panels::user-menu.profile.before">Profile Before</option>
                                <option value="panels::user-menu.profile.after">Profile After</option>
                            </optgroup>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

            </div>

            {{-- Column 2: Display --}}
            <div class="space-y-3 p-4">
                <h4 class="{{ $sectionClass }}">Display</h4>

                <div class="space-y-1 {{ $wrapperClass }}">
                    <span class="{{ $labelClass }}">Mode</span>
                    <x-filament::input.wrapper>
                        <x-filament::input.select class="{{ $selectClass }}" wire:model.live="displayMode">
                            <option value="dropdown">Dropdown</option>
                            <option value="modal">Modal</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <div @class(['space-y-1 ' . $wrapperClass, 'opacity-40 pointer-events-none' => ! $isModal])>
                    <span class="{{ $labelClass }}">Width</span>
                    <x-filament::input.wrapper :disabled="! $isModal">
                        <x-filament::input.select class="{{ $selectClass }}" wire:model.live="modalWidth" :disabled="! $isModal">
                            <option value="xs">xs</option>
                            <option value="sm">sm</option>
                            <option value="md">md</option>
                            <option value="lg">lg</option>
                            <option value="xl">xl</option>
                            <option value="2xl">2xl</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <div @class(['space-y-1 ' . $wrapperClass, 'opacity-40 pointer-events-none' => ! $isModal])>
                    <span class="{{ $labelClass }}">Columns</span>
                    <x-filament::input.wrapper :disabled="! $isModal">
                        <x-filament::input.select class="{{ $selectClass }}" wire:model.live="columns" :disabled="! $isModal">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <div @class(['flex items-center justify-between', 'opacity-40 pointer-events-none' => ! $isModal])>
                    <span class="{{ $labelClass }}">Slide-over</span>
                    <x-filament::toggle class="{{ $toggleClass }}" :state="$modalSlideOver ? 'true' : 'false'" wire:click="$toggle('modalSlideOver')" :disabled="! $isModal" />
                </div>
            </div>

            {{-- Column 3: Appearance --}}
            <div class="space-y-3 p-4">
                <h4 class="{{ $sectionClass }}">Appearance</h4>

                <div class="flex items-center justify-between">
                    <span class="{{ $labelClass }}">Circular</span>
                    <x-filament::toggle class="{{ $toggleClass }}" :state="$circular ? 'true' : 'false'" wire:click="$toggle('circular')" />
                </div>

                <div class="flex items-center justify-between">
                    <span class="{{ $labelClass }}">Native Labels</span>
                    <x-filament::toggle class="{{ $toggleClass }}" :state="$nativeLabel ? 'true' : 'false'" wire:click="$toggle('nativeLabel')" />
                </div>

                <div class="flex items-center justify-between">
                    <span class="{{ $labelClass }}">Use Flags</span>
                    <x-filament::toggle class="{{ $toggleClass }}" :state="$useFlags ? 'true' : 'false'" wire:click="$toggle('useFlags')" />
                </div>

                <div @class(['flex items-center justify-between', 'opacity-40 pointer-events-none' => ! $useFlags])>
                    <span class="{{ $labelClass }}">Flags Only</span>
                    <x-filament::toggle class="{{ $toggleClass }}" :state="$flagsOnly ? 'true' : 'false'" wire:click="$toggle('flagsOnly')" :disabled="! $useFlags" />
                </div>
            </div>

            {{-- Column 4: Outside Panels --}}
            <div class="space-y-3 p-4">
                <h4 class="{{ $sectionClass }}">Outside Panels</h4>

                <div class="flex items-center justify-between">
                    <span class="{{ $labelClass }}">Enabled</span>
                    <x-filament::toggle class="{{ $toggleClass }}" :state="$outsidePanels ? 'true' : 'false'" wire:click="$toggle('outsidePanels')" />
                </div>

                <div @class(['space-y-1 ' . $wrapperClass, 'opacity-40 pointer-events-none' => ! $outsidePanels])>
                    <span class="{{ $labelClass }}">Placement</span>
                    <x-filament::input.wrapper :disabled="! $outsidePanels">
                        <x-filament::input.select class="{{ $selectClass }}" wire:model.live="outsidePanelPlacement" :disabled="! $outsidePanels">
                            <option value="top-start">Top Start</option>
                            <option value="top-center">Top Center</option>
                            <option value="top-end">Top End</option>
                            <option value="bottom-start">Bottom Start</option>
                            <option value="bottom-center">Bottom Center</option>
                            <option value="bottom-end">Bottom End</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <div @class(['space-y-1 ' . $wrapperClass, 'opacity-40 pointer-events-none' => ! $outsidePanels])>
                    <span class="{{ $labelClass }}">Mode</span>
                    <x-filament::input.wrapper :disabled="! $outsidePanels">
                        <x-filament::input.select class="{{ $selectClass }}" wire:model.live="outsidePanelPlacementMode" :disabled="! $outsidePanels">
                            <option value="static">Static (default)</option>
                            <option value="pinned">Pinned</option>
                            <option value="relative">Relative</option>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>

                <div @class(['space-y-1 ' . $wrapperClass, 'opacity-40 pointer-events-none' => ! $outsidePanels])>
                    <span class="{{ $labelClass }}">Render Hook</span>
                    <x-filament::input.wrapper :disabled="! $outsidePanels">
                        <x-filament::input.select class="{{ $selectClass }}" wire:model.live="outsidePanelsRenderHook" :disabled="! $outsidePanels">
                            <option value="">Auto (from placement + mode)</option>
                            <optgroup label="Dock into user menu">
                                <option value="panels::user-menu.before">Before</option>
                                <option value="panels::user-menu.after">After</option>
                                <option value="panels::user-menu.profile.before">Profile Before</option>
                                <option value="panels::user-menu.profile.after">Profile After</option>
                            </optgroup>
                        </x-filament::input.select>
                    </x-filament::input.wrapper>
                </div>
            </div>
        </div>
    </div>
</div>

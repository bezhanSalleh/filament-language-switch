@php
    $selectClass = 'w-full rounded-md border-0 bg-white/6 py-1 pr-7 pl-2 text-[11px] text-gray-300 ring-1 ring-white/8 focus:ring-primary-500/50 disabled:opacity-40 disabled:cursor-not-allowed';
    $inputClass = 'w-full rounded-md border-0 bg-white/6 py-1 px-2 text-[11px] text-gray-300 ring-1 ring-white/8 placeholder:text-gray-600 focus:ring-primary-500/50';
    $checkClass = 'size-3.5 rounded border-0 bg-white/6 text-primary-500 ring-1 ring-white/8 focus:ring-primary-500/50 focus:ring-offset-0 disabled:opacity-40 disabled:cursor-not-allowed';
    $labelClass = 'text-[11px] text-gray-400';
    $isModal = $displayMode === 'modal';
@endphp

<div
    x-data="{ open: @entangle('isOpen') }"
    class="fixed bottom-4 end-4 z-50"
>
    <button
        type="button"
        x-on:click="open = ! open"
        x-tooltip="{ content: 'Language Switch Debug', theme: $store.theme }"
        class="flex size-10 items-center justify-center rounded-full bg-gray-950 text-gray-400 shadow ring-1 ring-white/10 transition hover:text-white hover:ring-white/20"
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
        class="w-80 md:w-[48rem] max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl bg-black shadow-2xl ring-1 ring-white/5"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-2.5">
            <span class="text-xs font-semibold text-[#fe984a]">Language Switch</span>
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-amber-400/10 px-1.5 py-px text-[9px] font-bold tracking-wide text-amber-400">DEBUG</span>
                <button type="button" wire:click="resetDebug" class="rounded px-1.5 py-0.5 text-[10px] font-medium text-gray-500 transition hover:bg-white/6 hover:text-gray-300">Reset</button>
            </div>
        </div>

        <div class="h-px bg-white/6"></div>

        <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-white/6">

            {{-- Column 1: Trigger --}}
            <div class="space-y-2.5 p-4">
                <h4 class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">Trigger</h4>

                <label class="flex items-center justify-between">
                    <span class="{{ $labelClass }}">Topbar</span>
                    <input type="checkbox" wire:model.live="topbar" class="{{ $checkClass }}">
                </label>

                <div class="space-y-1">
                    <span class="{{ $labelClass }}">Style</span>
                    <select wire:model.live="triggerStyle" class="{{ $selectClass }}">
                        <option value="">Auto</option>
                        <option value="icon">Icon</option>
                        <option value="icon-label">Icon + Label</option>
                        <option value="avatar">Avatar</option>
                        <option value="avatar-label">Avatar + Label</option>
                        <option value="flag" @disabled(! $useFlags)>Flag</option>
                        <option value="flag-label" @disabled(! $useFlags)>Flag + Label</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <span class="{{ $labelClass }}">Icon</span>
                    <input type="text" wire:model.live.debounce.500ms="triggerIcon" placeholder="Default" class="{{ $inputClass }}">
                </div>

                <div class="space-y-1">
                    <span class="{{ $labelClass }}">Render Hook</span>
                    <select wire:model.live="renderHook" class="{{ $selectClass }}">
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
                            <option value="panels::sidebar.footer">Footer</option>
                        </optgroup>
                        <optgroup label="User Menu">
                            <option value="panels::user-menu.before">Before</option>
                            <option value="panels::user-menu.after">After</option>
                            <option value="panels::user-menu.profile.before">Profile Before</option>
                            <option value="panels::user-menu.profile.after">Profile After</option>
                        </optgroup>
                    </select>
                </div>
            </div>

            {{-- Column 2: Display --}}
            <div class="space-y-2.5 p-4">
                <h4 class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">Display</h4>

                <div class="space-y-1">
                    <span class="{{ $labelClass }}">Mode</span>
                    <select wire:model.live="displayMode" class="{{ $selectClass }}">
                        <option value="dropdown">Dropdown</option>
                        <option value="modal">Modal</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <span class="{{ $labelClass }} @if(! $isModal) opacity-40 @endif">Width</span>
                    <select wire:model.live="modalWidth" class="{{ $selectClass }}" @disabled(! $isModal)>
                        <option value="xs">xs</option>
                        <option value="sm">sm</option>
                        <option value="md">md</option>
                        <option value="lg">lg</option>
                        <option value="xl">xl</option>
                        <option value="2xl">2xl</option>
                    </select>
                </div>

                <div class="space-y-1">
                    <span class="{{ $labelClass }} @if(! $isModal) opacity-40 @endif">Columns</span>
                    <select wire:model.live="columns" class="{{ $selectClass }}" @disabled(! $isModal)>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </div>

                <label class="flex items-center justify-between @if(! $isModal) opacity-40 @endif">
                    <span class="{{ $labelClass }}">Slide-over</span>
                    <input type="checkbox" wire:model.live="modalSlideOver" class="{{ $checkClass }}" @disabled(! $isModal)>
                </label>
            </div>

            {{-- Column 3: Appearance --}}
            <div class="space-y-2.5 p-4">
                <h4 class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">Appearance</h4>

                <label class="flex items-center justify-between">
                    <span class="{{ $labelClass }}">Circular</span>
                    <input type="checkbox" wire:model.live="circular" class="{{ $checkClass }}">
                </label>

                <label class="flex items-center justify-between">
                    <span class="{{ $labelClass }}">Native Labels</span>
                    <input type="checkbox" wire:model.live="nativeLabel" class="{{ $checkClass }}">
                </label>

                <label class="flex items-center justify-between">
                    <span class="{{ $labelClass }}">Use Flags</span>
                    <input type="checkbox" wire:model.live="useFlags" class="{{ $checkClass }}">
                </label>

                <label class="flex items-center justify-between @if(! $useFlags) opacity-40 @endif">
                    <span class="{{ $labelClass }}">Flags Only</span>
                    <input type="checkbox" wire:model.live="flagsOnly" class="{{ $checkClass }}" @disabled(! $useFlags)>
                </label>
            </div>
        </div>
    </div>
</div>

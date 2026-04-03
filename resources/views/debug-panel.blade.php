@php
    $selectClass = 'w-full rounded-lg border-0 bg-white/6 py-1.5 pr-8 pl-2.5 text-xs text-gray-300 ring-1 ring-white/8 focus:ring-primary-500/50';
    $toggleClass = 'relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500/50 focus:ring-offset-2 focus:ring-offset-black';
    $toggleDotClass = 'pointer-events-none inline-block size-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out';
@endphp

<div
    x-data="{ open: @entangle('isOpen') }"
    class="fixed bottom-4 end-4 z-50"
>
    {{-- Toggle button --}}
    <button
        type="button"
        x-on:click="open = ! open"
        x-tooltip="{ content: 'Language Switch Debug', theme: $store.theme }"
        class="flex size-10 items-center justify-center rounded-full bg-gray-950 text-gray-400 shadow-lg ring-1 ring-white/10 transition hover:text-white hover:ring-white/20"
    >
        {{
            \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::Language, attributes: new \Illuminate\View\ComponentAttributeBag([
                'class' => 'size-5',
            ]))
        }}
    </button>

    {{-- Panel --}}
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
        class="w-[36rem] max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl bg-black shadow-2xl ring-1 ring-white/8"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-3.5">
            <span class="text-sm font-semibold text-white">Language Switch</span>
            <div class="flex items-center gap-2">
                <span class="rounded-full bg-amber-400/10 px-2 py-0.5 text-[10px] font-bold tracking-wide text-amber-400">DEBUG</span>
                <button
                    type="button"
                    wire:click="resetDebug"
                    class="rounded-md px-2 py-1 text-[11px] font-medium text-gray-500 transition hover:bg-white/6 hover:text-gray-300"
                >
                    Reset
                </button>
            </div>
        </div>

        <div class="h-px bg-white/6"></div>

        {{-- Content: 2-column grid on md+, single column below --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-px bg-white/6">

            {{-- Left column: Trigger & Display --}}
            <div class="space-y-5 bg-black p-5">
                {{-- Panel --}}
                <div class="space-y-3">
                    <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-500">Panel</h4>

                    <div class="flex items-center justify-between">
                        <span class="text-[13px] text-gray-300">Topbar</span>
                        <button
                            type="button"
                            wire:click="$toggle('topbar')"
                            @class([$toggleClass, $topbar ? 'bg-primary-600' : 'bg-white/10'])
                            role="switch"
                            :aria-checked="@js($topbar)"
                        >
                            <span @class([$toggleDotClass, $topbar ? 'translate-x-4' : 'translate-x-0.5'])></span>
                        </button>
                    </div>
                </div>

                <div class="h-px bg-white/6"></div>

                {{-- Trigger --}}
                <div class="space-y-3">
                    <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-500">Trigger</h4>

                    <div class="flex items-center justify-between">
                        <span class="text-[13px] text-gray-300">Style</span>
                        <select wire:model.live="triggerStyle" class="{{ $selectClass }} w-auto">
                            <option value="">Auto</option>
                            <option value="icon">Icon</option>
                            <option value="icon-label">Icon + Label</option>
                            <option value="avatar">Avatar</option>
                            <option value="avatar-label">Avatar + Label</option>
                            @if ($useFlags)
                                <option value="flag">Flag</option>
                                <option value="flag-label">Flag + Label</option>
                            @endif
                        </select>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[13px] text-gray-300">Icon</span>
                        <input type="text" wire:model.live.debounce.500ms="triggerIcon" placeholder="Default" class="w-36 rounded-lg border-0 bg-white/6 py-1.5 px-2.5 text-xs text-gray-300 ring-1 ring-white/8 placeholder:text-gray-600 focus:ring-primary-500/50">
                    </div>

                    <label class="flex flex-col gap-1.5">
                        <span class="text-[13px] text-gray-300">Render Hook</span>
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
                                <option value="panels::sidebar.start">Start</option>
                            </optgroup>
                            <optgroup label="User Menu">
                                <option value="panels::user-menu.before">Before</option>
                                <option value="panels::user-menu.after">After</option>
                                <option value="panels::user-menu.profile.before">Profile Before</option>
                                <option value="panels::user-menu.profile.after">Profile After</option>
                            </optgroup>
                        </select>
                    </label>
                </div>
            </div>

            {{-- Right column: Display & Appearance --}}
            <div class="space-y-5 bg-black p-5">
                {{-- Display --}}
                <div class="space-y-3">
                    <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-500">Display</h4>

                    <div class="flex items-center justify-between">
                        <span class="text-[13px] text-gray-300">Mode</span>
                        <select wire:model.live="displayMode" class="{{ $selectClass }} w-auto">
                            <option value="dropdown">Dropdown</option>
                            <option value="modal">Modal</option>
                        </select>
                    </div>

                    @if ($displayMode === 'modal')
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-gray-300">Modal Width</span>
                            <select wire:model.live="modalWidth" class="{{ $selectClass }} w-auto">
                                <option value="xs">xs</option>
                                <option value="sm">sm</option>
                                <option value="md">md</option>
                                <option value="lg">lg</option>
                                <option value="xl">xl</option>
                                <option value="2xl">2xl</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-gray-300">Columns</span>
                            <select wire:model.live="columns" class="{{ $selectClass }} w-auto">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-gray-300">Slide-over</span>
                            <button
                                type="button"
                                wire:click="$toggle('modalSlideOver')"
                                @class([$toggleClass, $modalSlideOver ? 'bg-primary-600' : 'bg-white/10'])
                                role="switch"
                            >
                                <span @class([$toggleDotClass, $modalSlideOver ? 'translate-x-4' : 'translate-x-0.5'])></span>
                            </button>
                        </div>
                    @endif
                </div>

                <div class="h-px bg-white/6"></div>

                {{-- Appearance --}}
                <div class="space-y-3">
                    <h4 class="text-[11px] font-semibold uppercase tracking-widest text-gray-500">Appearance</h4>

                    <div class="flex items-center justify-between">
                        <span class="text-[13px] text-gray-300">Circular</span>
                        <button
                            type="button"
                            wire:click="$toggle('circular')"
                            @class([$toggleClass, $circular ? 'bg-primary-600' : 'bg-white/10'])
                            role="switch"
                        >
                            <span @class([$toggleDotClass, $circular ? 'translate-x-4' : 'translate-x-0.5'])></span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[13px] text-gray-300">Native Labels</span>
                        <button
                            type="button"
                            wire:click="$toggle('nativeLabel')"
                            @class([$toggleClass, $nativeLabel ? 'bg-primary-600' : 'bg-white/10'])
                            role="switch"
                        >
                            <span @class([$toggleDotClass, $nativeLabel ? 'translate-x-4' : 'translate-x-0.5'])></span>
                        </button>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-[13px] text-gray-300">Use Flags</span>
                        <button
                            type="button"
                            wire:click="$toggle('useFlags')"
                            @class([$toggleClass, $useFlags ? 'bg-primary-600' : 'bg-white/10'])
                            role="switch"
                        >
                            <span @class([$toggleDotClass, $useFlags ? 'translate-x-4' : 'translate-x-0.5'])></span>
                        </button>
                    </div>

                    @if ($useFlags)
                        <div class="flex items-center justify-between">
                            <span class="text-[13px] text-gray-300">Flags Only</span>
                            <button
                                type="button"
                                wire:click="$toggle('flagsOnly')"
                                @class([$toggleClass, $flagsOnly ? 'bg-primary-600' : 'bg-white/10'])
                                role="switch"
                            >
                                <span @class([$toggleDotClass, $flagsOnly ? 'translate-x-4' : 'translate-x-0.5'])></span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $labelClass = 'text-xs text-gray-400';
    $sectionLabelClass = 'text-[10px] font-semibold uppercase tracking-widest transition-colors';
    $isModal = $displayMode === 'modal';
    $wrapperClass = '[&_.fi-input-wrp]:h-8! [&_.fi-input-wrp]:min-h-0!';
    $selectClass = 'text-xs! py-1!';
    $inputFieldClass = 'text-xs! py-1!';
    // LTR-only overrides with !important. The control panel forces dir="ltr" on its root,
    // so only LTR knob positions apply. rtl: variants are intentionally omitted because
    // Tailwind's rtl: selector matches via [dir=rtl] * (any ancestor), which breaks the
    // dir=ltr override inside an RTL app. Filament's own rtl: base rules have no !important
    // so our LTR overrides win in all directions.
    $toggleClass = 'items-center h-5 w-9 [&>:first-child]:size-3.5! [&.fi-toggle-on>:first-child]:translate-x-4! [&.fi-toggle-off>:first-child]:translate-x-0.5!';

    // Position the panel out of the way of previews:
    //   - slide-over on → center horizontally (slide-over comes from the document end edge)
    //   - LTR document  → dock right
    //   - RTL document  → dock left
    // Raw left/right (not logical start/end) because the panel forces dir="ltr" on itself,
    // so end-4 would always resolve to "right" regardless of the document direction.
    //
    // Uses the *applied* slide-over state (committed on mount/apply) rather than the
    // live form values so the panel doesn't reposition while the user is still editing
    // dirty values in non-live mode — it only moves once the preview actually changes.
    $isRtl = __('filament-panels::layout.direction') === 'rtl';
    $isSlideOverPreview = $appliedDisplayMode === 'modal' && $appliedModalSlideOver;
    $positionClass = match (true) {
        $isSlideOverPreview => 'inset-x-0 mx-auto',
        $isRtl              => 'left-4',
        default             => 'right-4',
    };
@endphp

<div
    x-data="{
        section: $persist('trigger').as('fls-cp-section')
    }"
    dir="ltr"
    class="fls-control-panel dark fixed bottom-4 {{ $positionClass }} z-50 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-2xl bg-black shadow-2xl ring-1 ring-white/5"
>
    <style>
        @keyframes fls-control-panel-enter {
            from {
                opacity: 0;
                transform: translateY(12px) scale(.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .fls-control-panel {
            animation: fls-control-panel-enter .32s cubic-bezier(.16, 1, .3, 1) both;
        }

        /* CSS grid 0fr → 1fr trick for smooth, coordinated accordion height transitions —
           avoids the measurement flicker of JS-driven height animations and keeps the
           close/open pair perfectly in sync regardless of content height. */
        .fls-control-panel-body {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows .28s cubic-bezier(.4, 0, .2, 1);
        }

        .fls-control-panel-body[data-open="true"] {
            grid-template-rows: 1fr;
        }

        .fls-control-panel-body>.fls-control-panel-body-inner {
            overflow: hidden;
            min-height: 0;
        }
    </style>

    <div class="px-4 pt-3 pb-2.5">
        <div class="flex items-center gap-2">
            <span class="text-sm font-semibold text-primary-400">Language Switch</span>
            <span class="rounded-full bg-warning-400/10 px-1.5 py-px text-[9px] font-bold tracking-wide text-warning-400">DEV</span>
        </div>
        <p class="mt-0.5 text-[10px] text-gray-500">Preview and configure the language switch without touching code.</p>
    </div>

    <div class="h-px bg-white/6"></div>

    <div class="max-h-[70vh] overflow-y-auto divide-y divide-white/6">

        <div>
            <button type="button" x-on:click="section = section === 'trigger' ? null : 'trigger'" class="flex w-full items-center justify-between px-4 py-3 transition hover:bg-white/5">
                <span class="{{ $sectionLabelClass }}" x-bind:class="section === 'trigger' ? 'text-primary-400' : 'text-gray-500'">Trigger</span>
                <svg x-bind:class="{ 'rotate-180': section === 'trigger' }" class="size-3.5 text-gray-500 transition-transform duration-300 ease-out" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div data-open="true" x-bind:data-open="section === 'trigger' ? 'true' : 'false'" class="fls-control-panel-body">
                <div class="fls-control-panel-body-inner">
                    <div class="space-y-3 p-4">
                        <div class="{{ $wrapperClass }}">
                            <x-filament::input.wrapper class="[&_.fi-input-wrp-content-ctn]:items-center! [&_.fi-input-wrp-content-ctn]:flex [&_.fi-input-wrp-content-ctn]:justify-end [&_.fi-input-wrp-content-ctn]:pe-2">
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Topbar</span>
                                </x-slot>
                                <x-filament::toggle class="{{ $toggleClass }}" :state="$topbar ? 'true' : 'false'" wire:click="$toggle('topbar')" />
                            </x-filament::input.wrapper>
                        </div>

                        <div class="{{ $wrapperClass }}">
                            <x-filament::input.wrapper>
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Style</span>
                                </x-slot>
                                <x-filament::input.select class="{{ $selectClass }}" wire:model.live="triggerStyle">
                                    <option value="">Auto</option>
                                    <option value="icon">Icon</option>
                                    <option value="icon-label">Icon + Label</option>
                                    <option value="avatar">Avatar</option>
                                    <option value="avatar-label">Avatar + Label</option>
                                    <option value="flag" @disabled(!$useFlags)>Flag</option>
                                    <option value="flag-label" @disabled(!$useFlags)>Flag + Label</option>
                                    <option value="label">Label Only</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>

                        <div class="{{ $wrapperClass }}">
                            <x-filament::input.wrapper>
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Icon</span>
                                </x-slot>
                                <x-filament::input class="{{ $inputFieldClass }}" type="text" wire:model="triggerIcon" x-on:blur="$wire.applyIcon()" x-on:keydown.enter.prevent="$wire.applyIcon()" placeholder="Default" />
                            </x-filament::input.wrapper>
                        </div>

                        <div class="{{ $wrapperClass }}">
                            <x-filament::input.wrapper>
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Hook</span>
                                </x-slot>
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
                </div>
            </div>
        </div>

        <div>
            <button type="button" x-on:click="section = section === 'display' ? null : 'display'" class="flex w-full items-center justify-between px-4 py-3 transition hover:bg-white/5">
                <span class="{{ $sectionLabelClass }}" x-bind:class="section === 'display' ? 'text-primary-400' : 'text-gray-500'">Display</span>
                <svg x-bind:class="{ 'rotate-180': section === 'display' }" class="size-3.5 text-gray-500 transition-transform duration-300 ease-out" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div x-bind:data-open="section === 'display' ? 'true' : 'false'" class="fls-control-panel-body">
                <div class="fls-control-panel-body-inner">
                    <div class="space-y-3 p-4">
                        <div class="{{ $wrapperClass }}">
                            <x-filament::input.wrapper>
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Mode</span>
                                </x-slot>
                                <x-filament::input.select class="{{ $selectClass }}" wire:model.live="displayMode">
                                    <option value="dropdown">Dropdown</option>
                                    <option value="modal">Modal</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>

                        <div @class([$wrapperClass, 'opacity-40 pointer-events-none' => !$isModal])>
                            <x-filament::input.wrapper :disabled="!$isModal">
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Width</span>
                                </x-slot>
                                <x-filament::input.select class="{{ $selectClass }}" wire:model.live="modalWidth" :disabled="!$isModal">
                                    <option value="xs">xs</option>
                                    <option value="sm">sm</option>
                                    <option value="md">md</option>
                                    <option value="lg">lg</option>
                                    <option value="xl">xl</option>
                                    <option value="2xl">2xl</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>

                        <div @class([$wrapperClass, 'opacity-40 pointer-events-none' => !$isModal])>
                            <x-filament::input.wrapper :disabled="!$isModal">
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Columns</span>
                                </x-slot>
                                <x-filament::input.select class="{{ $selectClass }}" wire:model.live="columns" :disabled="!$isModal">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>

                        <div @class(['flex items-center justify-between', 'opacity-40 pointer-events-none' => !$isModal])>
                            <span class="{{ $labelClass }}">Slide-over</span>
                            <x-filament::toggle class="{{ $toggleClass }}" :state="$modalSlideOver ? 'true' : 'false'" wire:click="$toggle('modalSlideOver')" :disabled="!$isModal" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <button type="button" x-on:click="section = section === 'appearance' ? null : 'appearance'" class="flex w-full items-center justify-between px-4 py-3 transition hover:bg-white/5">
                <span class="{{ $sectionLabelClass }}" x-bind:class="section === 'appearance' ? 'text-primary-400' : 'text-gray-500'">Appearance</span>
                <svg x-bind:class="{ 'rotate-180': section === 'appearance' }" class="size-3.5 text-gray-500 transition-transform duration-300 ease-out" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div x-bind:data-open="section === 'appearance' ? 'true' : 'false'" class="fls-control-panel-body">
                <div class="fls-control-panel-body-inner">
                    <div class="space-y-3 p-4">
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

                        <div class="{{ $wrapperClass }}">
                            <x-filament::input.wrapper>
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Items</span>
                                </x-slot>
                                <x-filament::input.select class="{{ $selectClass }}" wire:model.live="itemStyle">
                                    <option value="">Auto</option>
                                    <option value="flag-with-label" @disabled(!$useFlags)>Flag + Label</option>
                                    <option value="flag-only" @disabled(!$useFlags)>Flag Only</option>
                                    <option value="avatar-with-label">Avatar + Label</option>
                                    <option value="avatar-only">Avatar Only</option>
                                    <option value="label-only">Label Only</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <button type="button" x-on:click="section = section === 'outside' ? null : 'outside'" class="flex w-full items-center justify-between px-4 py-3 transition hover:bg-white/5">
                <span class="{{ $sectionLabelClass }}" x-bind:class="section === 'outside' ? 'text-primary-400' : 'text-gray-500'">Outside Panels</span>
                <svg x-bind:class="{ 'rotate-180': section === 'outside' }" class="size-3.5 text-gray-500 transition-transform duration-300 ease-out" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <div x-bind:data-open="section === 'outside' ? 'true' : 'false'" class="fls-control-panel-body">
                <div class="fls-control-panel-body-inner">
                    <div class="space-y-3 p-4">
                        <div class="{{ $wrapperClass }}">
                            <x-filament::input.wrapper class="[&_.fi-input-wrp-content-ctn]:items-center! [&_.fi-input-wrp-content-ctn]:flex [&_.fi-input-wrp-content-ctn]:justify-end [&_.fi-input-wrp-content-ctn]:pe-2">
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Enabled</span>
                                </x-slot>
                                <x-filament::toggle class="{{ $toggleClass }}" :state="$outsidePanels ? 'true' : 'false'" wire:click="$toggle('outsidePanels')" />
                            </x-filament::input.wrapper>
                        </div>

                        <div @class([$wrapperClass, 'opacity-40 pointer-events-none' => !$outsidePanels])>
                            <x-filament::input.wrapper :disabled="!$outsidePanels">
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Placement</span>
                                </x-slot>
                                <x-filament::input.select class="{{ $selectClass }}" wire:model.live="outsidePanelPlacement" :disabled="!$outsidePanels">
                                    <option value="top-start">Top Start</option>
                                    <option value="top-center">Top Center</option>
                                    <option value="top-end">Top End</option>
                                    <option value="bottom-start">Bottom Start</option>
                                    <option value="bottom-center">Bottom Center</option>
                                    <option value="bottom-end">Bottom End</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>

                        <div @class([$wrapperClass, 'opacity-40 pointer-events-none' => !$outsidePanels])>
                            <x-filament::input.wrapper :disabled="!$outsidePanels">
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Mode</span>
                                </x-slot>
                                <x-filament::input.select class="{{ $selectClass }}" wire:model.live="outsidePanelPlacementMode" :disabled="!$outsidePanels">
                                    <option value="static">Static (default)</option>
                                    <option value="pinned">Pinned</option>
                                    <option value="relative">Relative</option>
                                </x-filament::input.select>
                            </x-filament::input.wrapper>
                        </div>

                        <div @class([$wrapperClass, 'opacity-40 pointer-events-none' => !$outsidePanels])>
                            <x-filament::input.wrapper :disabled="!$outsidePanels">
                                <x-slot name="prefix">
                                    <span class="{{ $labelClass }}">Hook</span>
                                </x-slot>
                                <x-filament::input.select class="{{ $selectClass }}" wire:model.live="outsidePanelsRenderHook" :disabled="!$outsidePanels">
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
    </div>

    <div class="flex items-center justify-between gap-2 border-t border-white/6 px-4 py-2">
        <a
            href="https://github.com/sponsors/bezhanSalleh"
            target="_blank"
            rel="noopener noreferrer"
            class="group flex items-center gap-1.5 text-[10px] text-gray-500"
        >
            <svg class="size-3 text-gray-500 transition group-hover:text-pink-400" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
            </svg>
            <span>Sponsor <span class="font-medium text-gray-400 transition group-hover:text-primary-300">bezhanSalleh</span> on GitHub</span>
        </a>
        <div class="flex items-center gap-2">
            @if (!$live)
                <button type="button" wire:click="applyOverrides" @class([
                    'rounded px-1.5 py-0.5 text-[10px] font-semibold transition',
                    'bg-primary-500/20 text-primary-300 hover:bg-primary-500/30' => $isDirty,
                    'text-gray-600 hover:text-gray-400' => !$isDirty,
                ])>Apply</button>
            @endif
            <button type="button" wire:click="resetOverrides" class="rounded px-1.5 py-0.5 text-[10px] font-medium text-gray-500 transition hover:bg-white/6 hover:text-gray-300">Reset</button>
        </div>
    </div>
</div>
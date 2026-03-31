<div
    x-data="{ open: @entangle('isOpen') }"
    class="fixed bottom-4 end-4 z-50"
>
    {{-- Toggle button --}}
    <button
        type="button"
        x-on:click="open = ! open"
        x-tooltip="{ content: 'Language Switch Debug', theme: $store.theme }"
        @class([
            'flex size-10 items-center justify-center rounded-full shadow-lg transition',
            'bg-black text-gray-400 ring-1 ring-white/10 hover:text-white hover:ring-white/20',
        ])
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
        x-anchor.bottom-end.offset.8="$refs.trigger ?? $el.previousElementSibling"
        dir="ltr"
        class="w-72 overflow-hidden rounded-2xl bg-black shadow-2xl ring-1 ring-white/[0.08]"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3">
            <span class="text-sm font-semibold text-white">Language Switch</span>
            <span class="rounded-full bg-amber-400/10 px-2 py-0.5 text-[10px] font-bold tracking-wide text-amber-400">DEBUG</span>
        </div>

        <div class="max-h-[60vh] space-y-4 overflow-y-auto px-4 pb-4">
            {{-- Panel --}}
            <fieldset class="space-y-2.5">
                <legend class="text-[11px] font-semibold uppercase tracking-widest text-gray-500">Panel</legend>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Topbar</span>
                    <input type="checkbox" wire:model.live="topbar" class="size-4 rounded border-0 bg-white/[0.06] text-primary-500 ring-1 ring-white/[0.08] focus:ring-primary-500/50 focus:ring-offset-black">
                </label>
            </fieldset>

            {{-- Display --}}
            <fieldset class="space-y-2.5">
                <legend class="text-[11px] font-semibold uppercase tracking-widest text-gray-500">Display</legend>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Mode</span>
                    <select wire:model.live="displayMode" class="rounded-lg border-0 bg-white/[0.06] py-1.5 pr-8 pl-2.5 text-xs text-gray-300 ring-1 ring-white/[0.08] focus:ring-primary-500/50">
                        <option value="dropdown">Dropdown</option>
                        <option value="modal">Modal</option>
                    </select>
                </label>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Inline</span>
                    <input type="checkbox" wire:model.live="inline" class="size-4 rounded border-0 bg-white/[0.06] text-primary-500 ring-1 ring-white/[0.08] focus:ring-primary-500/50 focus:ring-offset-black">
                </label>

                <label class="flex flex-col gap-1.5">
                    <span class="text-[13px] text-gray-300">Render Hook</span>
                    <select wire:model.live="renderHook" class="w-full rounded-lg border-0 bg-white/[0.06] py-1.5 pr-8 pl-2.5 text-xs text-gray-300 ring-1 ring-white/[0.08] focus:ring-primary-500/50">
                        <option value="">Auto (default)</option>
                        @if ($hasTopbar)
                            <optgroup label="Topbar">
                                <option value="panels::global-search.after">Global Search After</option>
                                <option value="panels::global-search.before">Global Search Before</option>
                                <option value="panels::topbar.start">Topbar Start</option>
                                <option value="panels::topbar.end">Topbar End</option>
                            </optgroup>
                        @endif
                        <optgroup label="Sidebar Nav">
                            <option value="panels::sidebar.nav.start">Sidebar Nav Start</option>
                            <option value="panels::sidebar.nav.end">Sidebar Nav End</option>
                        </optgroup>
                        <optgroup label="Sidebar">
                            <option value="panels::sidebar.footer">Sidebar Footer</option>
                            <option value="panels::sidebar.start">Sidebar Start</option>
                        </optgroup>
                        <optgroup label="User Menu">
                            <option value="panels::user-menu.before">User Menu Before</option>
                            <option value="panels::user-menu.after">User Menu After</option>
                            <option value="panels::user-menu.profile.before">Profile Before</option>
                            <option value="panels::user-menu.profile.after">Profile After</option>
                        </optgroup>
                    </select>
                </label>
            </fieldset>

            {{-- Appearance --}}
            <fieldset class="space-y-2.5">
                <legend class="text-[11px] font-semibold uppercase tracking-widest text-gray-500">Appearance</legend>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Circular</span>
                    <input type="checkbox" wire:model.live="circular" class="size-4 rounded border-0 bg-white/[0.06] text-primary-500 ring-1 ring-white/[0.08] focus:ring-primary-500/50 focus:ring-offset-black">
                </label>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Native Labels</span>
                    <input type="checkbox" wire:model.live="nativeLabel" class="size-4 rounded border-0 bg-white/[0.06] text-primary-500 ring-1 ring-white/[0.08] focus:ring-primary-500/50 focus:ring-offset-black">
                </label>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Use Flags</span>
                    <input type="checkbox" wire:model.live="useFlags" class="size-4 rounded border-0 bg-white/[0.06] text-primary-500 ring-1 ring-white/[0.08] focus:ring-primary-500/50 focus:ring-offset-black">
                </label>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Flags Only</span>
                    <input type="checkbox" wire:model.live="flagsOnly" class="size-4 rounded border-0 bg-white/[0.06] text-primary-500 ring-1 ring-white/[0.08] focus:ring-primary-500/50 focus:ring-offset-black">
                </label>
            </fieldset>

            {{-- Modal --}}
            <fieldset class="space-y-2.5">
                <legend class="text-[11px] font-semibold uppercase tracking-widest text-gray-500">Modal</legend>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Width</span>
                    <select wire:model.live="modalWidth" class="rounded-lg border-0 bg-white/[0.06] py-1.5 pr-8 pl-2.5 text-xs text-gray-300 ring-1 ring-white/[0.08] focus:ring-primary-500/50">
                        <option value="sm">sm</option>
                        <option value="md">md</option>
                        <option value="lg">lg</option>
                        <option value="xl">xl</option>
                        <option value="2xl">2xl</option>
                        <option value="3xl">3xl</option>
                    </select>
                </label>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Slide-over</span>
                    <input type="checkbox" wire:model.live="modalSlideOver" class="size-4 rounded border-0 bg-white/[0.06] text-primary-500 ring-1 ring-white/[0.08] focus:ring-primary-500/50 focus:ring-offset-black">
                </label>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Columns</span>
                    <select wire:model.live="columns" class="rounded-lg border-0 bg-white/[0.06] py-1.5 pr-8 pl-2.5 text-xs text-gray-300 ring-1 ring-white/[0.08] focus:ring-primary-500/50">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                </label>
            </fieldset>

            {{-- Sizing --}}
            <fieldset class="space-y-2.5">
                <legend class="text-[11px] font-semibold uppercase tracking-widest text-gray-500">Sizing</legend>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Flag Height</span>
                    <select wire:model.live="flagHeight" class="rounded-lg border-0 bg-white/[0.06] py-1.5 pr-8 pl-2.5 text-xs text-gray-300 ring-1 ring-white/[0.08] focus:ring-primary-500/50">
                        <option value="h-12">h-12</option>
                        <option value="h-16">h-16</option>
                        <option value="h-20">h-20</option>
                        <option value="h-24">h-24</option>
                    </select>
                </label>

                <label class="flex items-center justify-between">
                    <span class="text-[13px] text-gray-300">Avatar Size</span>
                    <select wire:model.live="charAvatarHeight" class="rounded-lg border-0 bg-white/[0.06] py-1.5 pr-8 pl-2.5 text-xs text-gray-300 ring-1 ring-white/[0.08] focus:ring-primary-500/50">
                        <option value="size-6">size-6</option>
                        <option value="size-8">size-8</option>
                        <option value="size-10">size-10</option>
                        <option value="size-12">size-12</option>
                    </select>
                </label>
            </fieldset>
        </div>

        {{-- Footer --}}
        <div class="px-4 py-3">
            <button
                type="button"
                wire:click="resetDebug"
                class="w-full rounded-lg bg-white/[0.06] px-3 py-2 text-center text-xs font-medium text-gray-400 ring-1 ring-white/[0.08] transition hover:bg-white/[0.1] hover:text-gray-300"
            >
                Reset
            </button>
        </div>
    </div>
</div>

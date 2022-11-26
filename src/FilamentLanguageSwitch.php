<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use Filament\Facades\Filament;
use Filament\Support\Concerns\Configurable;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class FilamentLanguageSwitch
{
    use Configurable;

    public static function boot(): void
    {
        $self = new static();
        $self->configure();
        $self->injectComponent();
    }

    public function injectComponent(): void
    {
        Livewire::component('switch-filament-language', Http\Livewire\SwitchFilamentLanguage::class);
        Filament::registerRenderHook(
            'global-search.end',
            fn (): string => Blade::render("@livewire('switch-filament-language')")
        );
    }
}

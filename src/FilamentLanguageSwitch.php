<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use Filament\Facades\Filament;
use Filament\Support\Concerns\Configurable;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class FilamentLanguageSwitch
{
    use Configurable;

    protected string $renderHookName = 'global-search.end';

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
            $this->getRenderHookName(),
            fn (): string => Blade::render("@livewire('switch-filament-language')")
        );
    }

    public function setRenderHookName(string $name): void
    {
        $this->renderHookName = $name;
    }

    public function getRenderHookName(): string
    {
        return $this->renderHookName;
    }
}

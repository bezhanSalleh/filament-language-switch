<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Http\Livewire\SwitchFilamentLanguage;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\Configurable;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class FilamentLanguageSwitchPlugin implements Plugin
{
    use Configurable;

    protected string $renderHookName = 'panels::global-search.after';
    protected bool $visible = true;

    public static function make(): static
    {
        $static = app(static::class);
        $static->configure();

        return $static;
    }

    public function visible($visible): static
    {
        $this->visible = $visible;

        return $this;
    }

    public function renderHookName(string $hookName): static
    {
        $this->renderHookName = $hookName;

        return $this;
    }

    public function getRenderHookName(): string
    {
        return $this->renderHookName;
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'filament-language-switch';
    }

    public function register(Panel $panel): void
    {
        Livewire::component('switch-filament-language', SwitchFilamentLanguage::class);

        if ($this->visible) {
            $panel
                ->renderHook(
                    name: $this->getRenderHookName(),
                    hook: fn (): string => Blade::render('@livewire(\'switch-filament-language\')')
                );
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }
}

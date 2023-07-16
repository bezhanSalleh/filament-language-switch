<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Blade;

class FilamentLanguageSwitchPlugin implements Plugin
{
    protected string $renderHookName = 'global-search.end';

    public static function make(): static
    {
        return app(static::class);
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

    public function getId(): string
    {
        return 'filament-language-switch';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->renderHook(
                $this->getRenderHookName(),
                fn (): string => Blade::render("@livewire('switch-filament-language')")
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }
}

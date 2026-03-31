<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch;

use BezhanSalleh\LanguageSwitch\Http\Livewire\LanguageSwitchComponent;
use BezhanSalleh\LanguageSwitch\Http\Middleware\SwitchLanguageLocale;
use Filament\Facades\Filament;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LanguageSwitchServiceProvider extends PackageServiceProvider
{
    public static string $name = 'language-switch';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        $this->registerPluginMiddleware();

        Livewire::component('language-switch-component', LanguageSwitchComponent::class);

        Filament::serving(function (): void {
            LanguageSwitch::boot();
        });
    }

    public function registerPluginMiddleware(): void
    {
        collect(LanguageSwitch::make()->getPanels())
            ->each($this->reorderCurrentPanelMiddlewareStack(...));
    }

    protected function reorderCurrentPanelMiddlewareStack(Panel $panel): void
    {
        $middlewareStack = invade($panel)->middleware;

        if (in_array(SwitchLanguageLocale::class, $middlewareStack, true)) {
            return;
        }

        $position = array_search(DispatchServingFilamentEvent::class, $middlewareStack, true);
        $position = $position !== false ? $position : 0;

        array_splice($middlewareStack, $position, 0, [SwitchLanguageLocale::class]);

        invade($panel)->middleware = $middlewareStack;
    }
}

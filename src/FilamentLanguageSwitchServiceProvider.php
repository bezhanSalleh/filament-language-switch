<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Http\Livewire\FilamentLanguageSwitch;
use BezhanSalleh\FilamentLanguageSwitch\Http\Middleware\SwitchLanguageLocale;
use Filament\Facades\Filament;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentLanguageSwitchServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-language-switch';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews();
    }

    public function packageBooted(): void
    {
        $this->registerPluginMiddleware();

        Livewire::component('filament-language-switch', FilamentLanguageSwitch::class);

        // FilamentAsset::register(
        //     assets: [
        //         Css::make('filament-language-switch', __DIR__ . '/../resources/dist/filament-language-switch.css'),
        //     ],
        //     package: 'bezhansalleh/filament-language-switch'
        // );

        Filament::serving(function () {
            LanguageSwitch::boot();
        });
    }

    public function registerPluginMiddleware(): void
    {
        collect(LanguageSwitch::make()->getPanels())
            ->each(fn ($panel) => $this->reorderCurrentPanelMiddlewareStack($panel));
    }

    protected function reorderCurrentPanelMiddlewareStack(Panel $panel): void
    {
        $middlewareStack = invade($panel)->getMiddleware();

        $middleware = SwitchLanguageLocale::class;
        $order = 'before';
        $referenceMiddleware = DispatchServingFilamentEvent::class;

        $middleware = is_array($middleware) ? collect($middleware) : collect([$middleware]);

        $middlewareCollection = collect($middlewareStack);

        $referenceIndex = $middlewareCollection->search($referenceMiddleware);
        $position = $order === 'before' ? $referenceIndex : $referenceIndex + 1;
        $position = $referenceMiddleware === null || $referenceIndex === false ? ($order === 'after' ? $middlewareCollection->count() : 0) : $position;

        invade($panel)->middleware = $middlewareCollection
            ->slice(0, $position)
            ->concat($middleware)
            ->concat($middlewareCollection->slice($position))
            ->toArray();
    }
}

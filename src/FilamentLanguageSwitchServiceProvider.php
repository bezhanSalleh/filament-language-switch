<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Http\Middleware\SwitchLanguageLocale;
use Filament\Facades\Filament;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentLanguageSwitchServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-language-switch';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        $this->registerPluginMiddleware();
    }

    public function registerPluginMiddleware(): void
    {
        collect(Filament::getPanels())
            ->filter(fn ($panel) => $panel->hasPlugin(static::$name))
            ->each(fn ($panel) => $this->reorderCurrentPanelMiddlewareStack($panel));

        FilamentAsset::register(
            assets: [
                Css::make('filament-language-switch', __DIR__ . '/../resources/dist/filament-language-switch.css'),
            ],
            package: 'bezhansalleh/filament-language-switch'
        );
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

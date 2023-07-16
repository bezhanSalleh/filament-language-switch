<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Http\Middleware\SwitchLanguageLocale;
use Filament\Facades\Filament;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
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
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        $this->registerSwitchLanguageMiddleware();

        Livewire::component('switch-filament-language', Http\Livewire\SwitchFilamentLanguage::class);

        FilamentAsset::register([
            Css::make('filament-language-switch', __DIR__ . '/../resources/dist/filament-language-switch.css'),
        ], 'bezhansalleh/filament-language-switch');
    }

    public function registerSwitchLanguageMiddleware(): void
    {
        $middlewareStack = Filament::getCurrentPanel()->getMiddleware();
        $switchLanguageIndex = array_search(SwitchLanguageLocale::class, $middlewareStack);
        $dispatchServingFilamentEventIndex = array_search(DispatchServingFilamentEvent::class, $middlewareStack);

        if ($switchLanguageIndex === false || $switchLanguageIndex > $dispatchServingFilamentEventIndex) {

            $middlewareStack = array_filter($middlewareStack, function ($middleware) {
                return $middleware !== SwitchLanguageLocale::class;
            });

            array_splice($middlewareStack, $dispatchServingFilamentEventIndex, 0, [SwitchLanguageLocale::class]);

            Filament::getCurrentPanel()->middleware($middlewareStack);
        }
    }
}

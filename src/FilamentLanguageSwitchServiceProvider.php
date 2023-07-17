<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

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
        Livewire::component('switch-filament-language', Http\Livewire\SwitchFilamentLanguage::class);

        FilamentAsset::register([
            Css::make('filament-language-switch', __DIR__ . '/../resources/dist/filament-language-switch.css'),
        ], 'bezhansalleh/filament-language-switch');
    }
}

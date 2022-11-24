<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentLanguageSwitchServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-language-switch';

    protected array $styles = [
        'filament-language-switch-styles' => __DIR__ . '/../resources/dist/filament-language-switch.css',
    ];

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        Filament::serving(fn () => FilamentLanguageSwitch::boot());
    }
}

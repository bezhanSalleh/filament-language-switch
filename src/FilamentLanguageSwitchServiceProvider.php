<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Http\Middleware\SwitchLanguageLocale;
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
        $this->registerSwitchLanguageMiddleware();
        Filament::serving(fn () => FilamentLanguageSwitch::boot());
    }

    public function registerSwitchLanguageMiddleware(): void
    {
        if (! array_key_exists(
            $key = SwitchLanguageLocale::class,
            $filamentMiddlewares = config('filament.middleware.base')
        )) {
            $filamentMiddlewares[] = $key;
            config(['filament.middleware.base' => $filamentMiddlewares]);
        }
    }
}

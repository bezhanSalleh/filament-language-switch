<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Http\Middleware\SwitchLanguageLocale;
use Filament\Facades\Filament;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\PluginServiceProvider;
use Spatie\LaravelPackageTools\Package;

class FilamentLanguageSwitchServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-language-switch';

    protected array $styles = [
        'filament-language-switch-styles' => __DIR__.'/../resources/dist/filament-language-switch.css',
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
        $middlewareStack = config('filament.middleware.base');
        $switchLanguageIndex = array_search(SwitchLanguageLocale::class, $middlewareStack);
        $dispatchServingFilamentEventIndex = array_search(DispatchServingFilamentEvent::class, $middlewareStack);

        if ($switchLanguageIndex === false || $switchLanguageIndex > $dispatchServingFilamentEventIndex) {

            $middlewareStack = array_filter($middlewareStack, function ($middleware) {
                return $middleware !== SwitchLanguageLocale::class;
            });

            array_splice($middlewareStack, $dispatchServingFilamentEventIndex, 0, [SwitchLanguageLocale::class]);

            config(['filament.middleware.base' => $middlewareStack]);
        }
    }
}

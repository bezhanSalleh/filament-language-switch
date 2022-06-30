<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use BladeUI\Icons\Factory;
use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;

class FilamentLanguageSwitchServiceProvider extends PluginServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('filament-language-switch')
            ->hasConfigFile()
            ->hasViews();
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        Livewire::component('switch-filament-language', Http\Livewire\SwitchFilamentLanguage::class);

        Filament::registerRenderHook(
            'global-search.end',
            fn (): string => Blade::render("@livewire('switch-filament-language')")
        );

        if (! array_key_exists(
            $key = \BezhanSalleh\FilamentLanguageSwitch\Http\Middleware\SwitchLanguageLocale::class,
            $filamentMiddlewares = config('filament.middleware.base')
        )) {
            $filamentMiddlewares[] = $key;
            config(['filament.middleware.base' => $filamentMiddlewares]);
        }
    }
}

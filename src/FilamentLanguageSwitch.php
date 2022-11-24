<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Http\Middleware\SwitchLanguageLocale;
use Filament\Facades\Filament;
use Filament\Support\Concerns\Configurable;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class FilamentLanguageSwitch
{
    use Configurable;

    public static function boot(): void
    {
        $self = new static();
        $self->configure();
        $self->injectComponent();
        $self->registerSwitchLangueMiddleware();
    }

    public function injectComponent(): void
    {
        Livewire::component('switch-filament-language', Http\Livewire\SwitchFilamentLanguage::class);
        Filament::registerRenderHook(
            'global-search.end',
            fn (): string => Blade::render("@livewire('switch-filament-language')")
        );
    }

    public function registerSwitchLangueMiddleware(): void
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

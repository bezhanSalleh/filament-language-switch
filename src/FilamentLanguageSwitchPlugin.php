<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Http\Middleware\SwitchLanguageLocale;
use Filament\Contracts\Plugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Illuminate\Support\Facades\Blade;

class FilamentLanguageSwitchPlugin implements Plugin
{
    protected string $renderHookName = 'global-search.end';

    public static function make(): static
    {
        return app(static::class);
    }

    public function renderHookName(string $hookName): static
    {
        $this->renderHookName = $hookName;

        return $this;
    }

    public function getRenderHookName(): string
    {
        return $this->renderHookName;
    }

    public static function registerPluginMiddlewareBeforeDispatchServingFilamentEvent(Panel $panel): void
    {
        $middleware = invade($panel)->middleware;

        $switchLanguageLocaleMiddleware = SwitchLanguageLocale::class;
        $dispatchServingFilamentEventMiddleware = DispatchServingFilamentEvent::class;

        $switchLanguageLocaleIndex = array_search($switchLanguageLocaleMiddleware, $middleware);
        $dispatchServingFilamentEventIndex = array_search($dispatchServingFilamentEventMiddleware, $middleware);

        if ($switchLanguageLocaleIndex !== false && $dispatchServingFilamentEventIndex !== false) {
            if ($switchLanguageLocaleIndex > $dispatchServingFilamentEventIndex) {
                $middleware[$dispatchServingFilamentEventIndex] = $switchLanguageLocaleMiddleware;
                $middleware[$switchLanguageLocaleIndex] = $dispatchServingFilamentEventMiddleware;
            }
        }

        invade($panel)->middleware = $middleware;
    }

    public function getId(): string
    {
        return 'filament-language-switch';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->renderHook(
                $this->getRenderHookName(),
                fn (): string => Blade::render("@livewire('switch-filament-language')")
            )
            ->middleware([
                SwitchLanguageLocale::class,
            ]);

        static::registerPluginMiddlewareBeforeDispatchServingFilamentEvent($panel);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}

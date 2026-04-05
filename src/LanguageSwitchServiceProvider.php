<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch;

use BezhanSalleh\LanguageSwitch\Enums\DisplayMode;
use BezhanSalleh\LanguageSwitch\Enums\TriggerStyle;
use BezhanSalleh\LanguageSwitch\Http\Livewire\LanguageSwitchComponent;
use BezhanSalleh\LanguageSwitch\Http\Livewire\LanguageSwitchDebugPanel;
use BezhanSalleh\LanguageSwitch\Http\Middleware\SwitchLanguageLocale;
use Filament\Facades\Filament;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LanguageSwitchServiceProvider extends PackageServiceProvider
{
    public static string $name = 'language-switch';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasViews()
            ->hasTranslations();
    }

    public function packageBooted(): void
    {
        $this->registerPluginMiddleware();

        Livewire::component('language-switch-component', LanguageSwitchComponent::class);

        Filament::serving(function (): void {
            LanguageSwitch::boot();
        });

        if (app()->isLocal() && config('app.debug')) {
            $this->bootDebugPanel();
        }
    }

    public function registerPluginMiddleware(): void
    {
        collect(LanguageSwitch::make()->getPanels())
            ->each($this->reorderCurrentPanelMiddlewareStack(...));
    }

    protected function reorderCurrentPanelMiddlewareStack(Panel $panel): void
    {
        $middlewareStack = invade($panel)->middleware;

        if (in_array(SwitchLanguageLocale::class, $middlewareStack, true)) {
            return;
        }

        $position = array_search(DispatchServingFilamentEvent::class, $middlewareStack, true);
        $position = $position !== false ? $position : 0;

        array_splice($middlewareStack, $position, 0, [SwitchLanguageLocale::class]);

        invade($panel)->middleware = $middlewareStack;
    }

    protected function bootDebugPanel(): void
    {
        LanguageSwitch::configureUsing(function (LanguageSwitch $languageSwitch): void {
            $overrides = session('language-switch-debug', []);
            $panel = filament()->getCurrentOrDefaultPanel();
            if (empty($overrides)) {
                return;
            }

            if (isset($overrides['topbar'])) {
                $panel->topbar((bool) $overrides['topbar']);
            }

            if (isset($overrides['displayMode'])) {
                $languageSwitch->displayMode(DisplayMode::from($overrides['displayMode']));
            }

            if (isset($overrides['circular'])) {
                $languageSwitch->circular((bool) $overrides['circular']);
            }

            if (isset($overrides['columns'])) {
                $languageSwitch->columns((int) $overrides['columns']);
            }

            if (isset($overrides['nativeLabel'])) {
                $languageSwitch->nativeLabel((bool) $overrides['nativeLabel']);
            }

            if (isset($overrides['flagsOnly'])) {
                $languageSwitch->flagsOnly((bool) $overrides['flagsOnly']);
            }

            if (isset($overrides['useFlags']) && ! $overrides['useFlags']) {
                $languageSwitch->flags([]);
            }

            if (isset($overrides['modalWidth'])) {
                $languageSwitch->modalWidth((string) $overrides['modalWidth']);
            }

            if (isset($overrides['modalSlideOver'])) {
                $languageSwitch->modalSlideOver((bool) $overrides['modalSlideOver']);
            }

            if (isset($overrides['flagHeight'])) {
                $languageSwitch->flagHeight((string) $overrides['flagHeight']);
            }

            if (isset($overrides['avatarHeight'])) {
                $languageSwitch->avatarHeight((string) $overrides['avatarHeight']);
            }

            if (! empty($overrides['renderHook'])) {
                $languageSwitch->renderHook((string) $overrides['renderHook']);
            }

            if (! empty($overrides['triggerStyle']) || ! empty($overrides['triggerIcon'])) {
                $languageSwitch->trigger(
                    style: empty($overrides['triggerStyle']) ? null : TriggerStyle::from($overrides['triggerStyle']),
                    icon: empty($overrides['triggerIcon']) ? null : (string) $overrides['triggerIcon'],
                );
            }
        }, isImportant: true);

        Livewire::component('language-switch-debug-panel', LanguageSwitchDebugPanel::class);

        Filament::serving(function (): void {
            FilamentView::registerRenderHook(
                name: PanelsRenderHook::BODY_END,
                hook: fn (): string => Blade::render('<livewire:language-switch-debug-panel />'),
            );
        });
    }
}

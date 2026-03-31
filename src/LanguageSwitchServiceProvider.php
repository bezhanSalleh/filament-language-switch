<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch;

use BezhanSalleh\LanguageSwitch\Enums\DisplayMode;
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

        $this->bootDebugPanel();
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
        if (! app()->isLocal() || ! config('app.debug')) {
            return;
        }

        LanguageSwitch::configureUsing(function (LanguageSwitch $switch): void {
            $overrides = session('language-switch-debug', []);

            if (empty($overrides)) {
                return;
            }

            if (isset($overrides['topbar'])) {
                filament()->getCurrentOrDefaultPanel()->topbar((bool) $overrides['topbar']);
            }

            if (isset($overrides['displayMode'])) {
                $switch->displayMode(DisplayMode::from($overrides['displayMode']));
            }

            if (isset($overrides['circular'])) {
                $switch->circular((bool) $overrides['circular']);
            }

            if (isset($overrides['inline'])) {
                $switch->inline((bool) $overrides['inline']);
            }

            if (isset($overrides['columns'])) {
                $switch->columns((int) $overrides['columns']);
            }

            if (isset($overrides['nativeLabel'])) {
                $switch->nativeLabel((bool) $overrides['nativeLabel']);
            }

            if (isset($overrides['flagsOnly'])) {
                $switch->flagsOnly((bool) $overrides['flagsOnly']);
            }

            if (isset($overrides['useFlags']) && ! $overrides['useFlags']) {
                $switch->flags([]);
            }

            if (isset($overrides['modalWidth'])) {
                $switch->modalWidth((string) $overrides['modalWidth']);
            }

            if (isset($overrides['modalSlideOver'])) {
                $switch->modalSlideOver((bool) $overrides['modalSlideOver']);
            }

            if (isset($overrides['flagHeight'])) {
                $switch->flagHeight((string) $overrides['flagHeight']);
            }

            if (isset($overrides['charAvatarHeight'])) {
                $switch->charAvatarHeight((string) $overrides['charAvatarHeight']);
            }

            if (! empty($overrides['renderHook'])) {
                $switch->renderHook((string) $overrides['renderHook']);
            }

            if (! empty($overrides['triggerStyle'])) {
                $switch->triggerStyle((string) $overrides['triggerStyle']);
            }

            if (! empty($overrides['triggerIcon'])) {
                $switch->triggerIcon((string) $overrides['triggerIcon']);
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

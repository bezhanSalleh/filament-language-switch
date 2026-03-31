<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch;

use Closure;
use Filament\Support\Components\Component;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class LanguageSwitch extends Component
{
    use Concerns\HasAppearance;
    use Concerns\HasContent;
    use Concerns\HasDeprecatedOutsidePanel;
    use Concerns\HasLocales;
    use Concerns\HasModal;
    use Concerns\HasVisibility;

    public static function make(): static
    {
        $static = app(static::class);

        $static->visible();

        $static->displayLocale();

        $static->outsidePanelRoutes();

        $static->configure();

        return $static;
    }

    /**
     * Backward-compatible visible() that supports the deprecated $outsidePanels parameter.
     */
    public function visible(bool | Closure $insidePanels = true, bool | Closure $outsidePanels = false): static
    {
        $this->visibleInsidePanels = $insidePanels;

        $this->visibleOutsidePanels = $outsidePanels;

        return $this;
    }

    /**
     * @deprecated Use isVisible() instead.
     */
    public function isVisibleInsidePanels(): bool
    {
        return $this->isVisible();
    }

    public static function boot(): void
    {
        $static = static::make();

        if ($static->isVisible()) {
            FilamentView::registerRenderHook(
                name: $static->getResolvedRenderHook(),
                hook: fn (): string => Blade::render("<livewire:language-switch-component key='fls' />"),
            );
        }

        // Deprecated: outside panel support (kept for backward compatibility)
        if ($static->isVisibleOutsidePanels()) {
            FilamentView::registerRenderHook(
                name: $static->getOutsidePanelsRenderHook(),
                hook: fn (): string => Blade::render("<livewire:language-switch-component key='fls-outside' />"),
            );
        }
    }
}

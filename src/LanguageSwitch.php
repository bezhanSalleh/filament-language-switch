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
    use Concerns\HasControlPanel;
    use Concerns\HasLocales;
    use Concerns\HasModal;
    use Concerns\HasOutsidePanel;
    use Concerns\HasTriggerLayout;
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

    public function visible(bool | Closure $condition = true): static
    {
        $this->visibleInsidePanels = $condition;

        return $this;
    }

    public static function boot(): void
    {
        $static = static::make();

        if (! $static->isVisible()) {
            return;
        }

        FilamentView::registerRenderHook(
            name: $static->getResolvedRenderHook(),
            hook: fn (): string => Blade::render("<livewire:language-switch-component key='fls' />"),
        );
    }
}

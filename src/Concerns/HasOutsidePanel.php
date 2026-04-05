<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Concerns;

use BezhanSalleh\LanguageSwitch\Enums\Placement;
use BezhanSalleh\LanguageSwitch\Enums\PlacementMode;
use Closure;
use Filament\View\PanelsRenderHook;

trait HasOutsidePanel
{
    protected bool | Closure $visibleOutsidePanels = false;

    protected array | Closure | null $outsidePanelRoutes = null;

    protected Placement | Closure $outsidePanelPlacement = Placement::TopEnd;

    protected PlacementMode | Closure $outsidePanelPlacementMode = PlacementMode::Fixed;

    protected string | Closure | null $outsidePanelsRenderHook = null;

    public function visibleOutsidePanels(bool | Closure $condition = true): static
    {
        $this->visibleOutsidePanels = $condition;

        return $this;
    }

    public function outsidePanelRoutes(array | Closure | null $routes = null): static
    {
        $this->outsidePanelRoutes = $routes ?? [
            'auth.login',
            'auth.profile',
            'auth.register',
        ];

        return $this;
    }

    public function outsidePanelPlacement(
        Placement | Closure $placement,
        PlacementMode | Closure | null $mode = null,
    ): static {
        $this->outsidePanelPlacement = $placement;

        if ($mode !== null) {
            $this->outsidePanelPlacementMode = $mode;
        }

        return $this;
    }

    public function outsidePanelsRenderHook(Closure | string $hook): static
    {
        $this->outsidePanelsRenderHook = $hook;

        return $this;
    }

    public function isVisibleOutsidePanels(): bool
    {
        return (bool) $this->evaluate($this->visibleOutsidePanels)
            && str((string) request()->route()?->getName())->contains($this->getOutsidePanelRoutes())
            && $this->isCurrentPanelIncluded();
    }

    /**
     * @return array<int, string>
     */
    public function getOutsidePanelRoutes(): array
    {
        return collect((array) $this->evaluate($this->outsidePanelRoutes))
            ->reject(fn (string $route): bool => str($route)->contains('profile')
                && ! $this->getCurrentPanel()->isProfilePageSimple())
            ->values()
            ->toArray();
    }

    public function getOutsidePanelPlacement(): Placement
    {
        $placement = $this->evaluate($this->outsidePanelPlacement);

        return $placement instanceof Placement ? $placement : Placement::TopEnd;
    }

    public function getOutsidePanelPlacementMode(): PlacementMode
    {
        $mode = $this->evaluate($this->outsidePanelPlacementMode);

        return $mode instanceof PlacementMode ? $mode : PlacementMode::Fixed;
    }

    public function getOutsidePanelsRenderHook(): string
    {
        $hook = $this->evaluate($this->outsidePanelsRenderHook);

        if ($hook !== null) {
            return (string) $hook;
        }

        $placement = $this->getOutsidePanelPlacement();
        $mode = $this->getOutsidePanelPlacementMode();

        if ($mode === PlacementMode::Fixed
            && $placement === Placement::TopEnd
            && $this->shouldDockIntoSimpleLayoutUserMenu()
        ) {
            return PanelsRenderHook::USER_MENU_BEFORE;
        }

        return $placement->anchorHook();
    }

    protected function shouldDockIntoSimpleLayoutUserMenu(): bool
    {
        return filament()->auth()->check()
            && $this->getCurrentPanel()->hasUserMenu();
    }
}

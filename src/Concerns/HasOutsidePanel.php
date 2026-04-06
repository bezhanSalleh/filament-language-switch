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

    protected PlacementMode | Closure $outsidePanelPlacementMode = PlacementMode::Static;

    protected string | Closure | null $outsidePanelsRenderHook = null;

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

        return $mode instanceof PlacementMode ? $mode : PlacementMode::Static;
    }

    public function getOutsidePanelsRenderHook(): string
    {
        $hook = $this->evaluate($this->outsidePanelsRenderHook);

        if ($hook !== null) {
            return (string) $hook;
        }

        $placement = $this->getOutsidePanelPlacement();
        $mode = $this->getOutsidePanelPlacementMode();

        // Pinned + TopEnd + authed user menu → dock into user menu to avoid collision
        // with .fi-simple-layout-header (also anchored at top-0 end-0).
        if ($mode === PlacementMode::Pinned
            && $placement === Placement::TopEnd
            && $this->shouldDockIntoSimpleLayoutUserMenu()
        ) {
            return PanelsRenderHook::USER_MENU_BEFORE;
        }

        return $this->defaultOutsidePanelAnchorHook($placement, $mode);
    }

    protected function defaultOutsidePanelAnchorHook(Placement $placement, PlacementMode $placementMode): string
    {
        $isTop = $placement->isTop();

        // Pinned elements use position: fixed — body hooks give them a direct-body-child
        // parent, which is the most reliable containing block for fixed positioning.
        if ($placementMode === PlacementMode::Pinned) {
            return $isTop
                ? PanelsRenderHook::BODY_START
                : PanelsRenderHook::BODY_END;
        }

        // Static + Relative are in document flow. Anchor them inside .fi-simple-layout
        // (min-h-dvh flex-col) so they share the viewport with the form card instead of
        // extending the body height.
        return $isTop
            ? PanelsRenderHook::SIMPLE_LAYOUT_START
            : PanelsRenderHook::SIMPLE_LAYOUT_END;
    }

    protected function shouldDockIntoSimpleLayoutUserMenu(): bool
    {
        return filament()->auth()->check()
            && $this->getCurrentPanel()->hasUserMenu();
    }
}

<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Concerns;

use BezhanSalleh\LanguageSwitch\Enums\Placement;
use Closure;

trait HasDeprecatedOutsidePanel
{
    protected bool | Closure $visibleOutsidePanels = false;

    protected array | Closure | null $outsidePanelRoutes = null;

    /** @var Placement|Closure<string|Placement|null>|null */
    protected string | Closure | Placement | null $outsidePanelPlacement = Placement::TopRight;

    protected string | Closure $outsidePanelsRenderHook = 'panels::body.start';

    /**
     * @deprecated Use displayMode(DisplayMode::Inline) and renderHook() instead.
     */
    public function visibleOutsidePanels(bool | Closure $condition = true): static
    {
        $this->visibleOutsidePanels = $condition;

        return $this;
    }

    /**
     * @deprecated Use displayMode(DisplayMode::Inline) and renderHook() instead.
     */
    public function outsidePanelRoutes(array | Closure | null $routes = null): static
    {
        $this->outsidePanelRoutes = $routes ?? [
            'auth.login',
            'auth.profile',
            'auth.register',
        ];

        return $this;
    }

    /**
     * @deprecated Use displayMode(DisplayMode::Inline) and renderHook() instead.
     */
    public function outsidePanelPlacement(Placement $placement): static
    {
        $this->outsidePanelPlacement = $placement;

        return $this;
    }

    /**
     * @deprecated Use renderHook() instead.
     */
    public function outsidePanelsRenderHook(Closure | string $hook): static
    {
        $this->outsidePanelsRenderHook = $hook;

        return $this;
    }

    /**
     * @deprecated Use isVisible() instead.
     */
    public function isVisibleOutsidePanels(): bool
    {
        return $this->evaluate($this->visibleOutsidePanels)
            && str(request()->route()?->getName())->contains($this->getOutsidePanelRoutes())
            && $this->isCurrentPanelIncluded();
    }

    /**
     * @deprecated
     */
    public function getOutsidePanelRoutes(): array
    {
        return (array) $this->evaluate(
            $this->outsidePanelRoutes ?? [
                'auth.login',
                'auth.profile',
                'auth.register',
            ]
        );
    }

    /**
     * @deprecated
     */
    public function getOutsidePanelPlacement(): Placement
    {
        $outsidePanelPlacement = $this->evaluate($this->outsidePanelPlacement);

        return match (true) {
            $outsidePanelPlacement instanceof Placement => $outsidePanelPlacement,
            is_string($outsidePanelPlacement) => Placement::tryFrom($outsidePanelPlacement) ?? Placement::TopRight,
            default => Placement::TopRight
        };
    }

    /**
     * @deprecated
     */
    public function getOutsidePanelsRenderHook(): string
    {
        return (string) $this->evaluate($this->outsidePanelsRenderHook);
    }
}

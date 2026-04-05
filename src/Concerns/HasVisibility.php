<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Concerns;

use Closure;
use Filament\Panel;
use Filament\View\PanelsRenderHook;

trait HasVisibility
{
    protected bool | Closure $visibleInsidePanels = false;

    protected array | Closure $excludes = [];

    protected string | Closure | null $renderHook = null;

    public function excludes(array | Closure $excludes): static
    {
        $this->excludes = $excludes;

        return $this;
    }

    public function renderHook(Closure | string $hook): static
    {
        $this->renderHook = $hook;

        return $this;
    }

    public function isVisible(): bool
    {
        return $this->evaluate($this->visibleInsidePanels)
            && count($this->getLocales()) > 1
            && $this->isCurrentPanelIncluded();
    }

    public function getExcludes(): array
    {
        return (array) $this->evaluate($this->excludes);
    }

    public function getRenderHook(): ?string
    {
        $hook = $this->evaluate($this->renderHook);

        return $hook ? (string) $hook : null;
    }

    /**
     * Smart detection of the best render hook based on panel configuration.
     */
    public function getDefaultRenderHook(): string
    {
        $panel = $this->getCurrentPanel();
        $hasTopbar = $panel->hasTopbar();

        return match (true) {
            $this->isInline() => PanelsRenderHook::USER_MENU_PROFILE_AFTER,
            ! $hasTopbar => PanelsRenderHook::SIDEBAR_FOOTER,
            default => PanelsRenderHook::GLOBAL_SEARCH_AFTER,
        };
    }

    /**
     * @return array<string, Panel>
     */
    public function getPanels(): array
    {
        return collect(filament()->getPanels())
            ->reject(fn (Panel $panel): bool => in_array($panel->getId(), $this->getExcludes()))
            ->toArray();
    }

    public function getCurrentPanel(): Panel
    {
        return filament()->getCurrentOrDefaultPanel();
    }

    public function isCurrentPanelIncluded(): bool
    {
        return array_key_exists($this->getCurrentPanel()->getId(), $this->getPanels());
    }

    /**
     * Determine the render context based on the active render hook and panel config.
     * Delegates to HasTriggerLayout::classifyHook() which is the single source of truth.
     */
    public function getRenderContext(): string
    {
        return $this->classifyHook(
            $this->getResolvedRenderHook(),
            $this->getCurrentPanel()->hasTopbar(),
        )['context'];
    }

    public function getResolvedRenderHook(): string
    {
        return $this->getRenderHook() ?? $this->getDefaultRenderHook();
    }
}

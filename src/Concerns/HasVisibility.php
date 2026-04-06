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
        return $this->isVisibleInsidePanels()
            && count($this->getLocales()) > 1
            && $this->isCurrentPanelIncluded();
    }

    public function isVisibleInsidePanels(): bool
    {
        return (bool) $this->evaluate($this->visibleInsidePanels);
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

    public function getDefaultRenderHook(): string
    {
        return $this->getCurrentPanel()->hasTopbar()
            ? PanelsRenderHook::GLOBAL_SEARCH_AFTER
            : PanelsRenderHook::SIDEBAR_LOGO_AFTER;
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

    public function getRenderContext(): string
    {
        return $this->classifyHook(
            $this->getResolvedRenderHook(),
            $this->getCurrentPanel()->hasTopbar(),
        )['context'];
    }

    public function getResolvedRenderHook(): string
    {
        if ($this->isVisibleOutsidePanels()) {
            return $this->getOutsidePanelsRenderHook();
        }

        return $this->getRenderHook() ?? $this->getDefaultRenderHook();
    }
}

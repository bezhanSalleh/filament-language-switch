<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Concerns;

use Closure;

trait HasControlPanel
{
    protected bool | Closure $controlPanelEnabled = false;

    protected bool | Closure $controlPanelLive = true;

    public function controlPanel(
        bool | Closure $condition = true,
        bool | Closure $live = true,
    ): static {
        $this->controlPanelEnabled = $condition;
        $this->controlPanelLive = $live;

        return $this;
    }

    public function isControlPanelEnabled(): bool
    {
        return (bool) $this->evaluate($this->controlPanelEnabled);
    }

    public function isControlPanelLive(): bool
    {
        return (bool) $this->evaluate($this->controlPanelLive);
    }
}

<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Concerns;

use BezhanSalleh\LanguageSwitch\Enums\DisplayMode;
use Closure;

trait HasAppearance
{
    protected bool | Closure $isCircular = false;

    protected bool | Closure $isInline = false;

    protected string | Closure | DisplayMode $displayMode = DisplayMode::Dropdown;

    protected string | Closure | null $dropdownPlacement = null;

    protected int | Closure $columns = 1;

    protected string | Closure $maxHeight = 'max-content';

    protected string | Closure $flagHeight = 'h-16';

    protected string | Closure $charAvatarHeight = 'size-8';

    public function circular(bool | Closure $condition = true): static
    {
        $this->isCircular = $condition;

        return $this;
    }

    /**
     * Render the trigger as a menu item (user menu / sidebar nav item)
     * instead of a standalone button. The display mode (dropdown/modal)
     * controls what opens when the trigger is clicked.
     */
    public function inline(bool | Closure $condition = true): static
    {
        $this->isInline = $condition;

        return $this;
    }

    public function displayMode(DisplayMode | string | Closure $mode): static
    {
        $this->displayMode = $mode;

        return $this;
    }

    public function dropdownPlacement(string | Closure | null $placement): static
    {
        $this->dropdownPlacement = $placement;

        return $this;
    }

    public function columns(int | Closure $columns): static
    {
        $this->columns = $columns;

        return $this;
    }

    public function maxHeight(Closure | string $height): static
    {
        $this->maxHeight = $height;

        return $this;
    }

    public function isCircular(): bool
    {
        return (bool) $this->evaluate($this->isCircular);
    }

    public function isInline(): bool
    {
        return (bool) $this->evaluate($this->isInline);
    }

    public function getDisplayMode(): DisplayMode
    {
        $mode = $this->evaluate($this->displayMode);

        if ($mode instanceof DisplayMode) {
            return $mode;
        }

        return DisplayMode::tryFrom($mode) ?? DisplayMode::Dropdown;
    }

    public function getDropdownPlacement(): ?string
    {
        return $this->evaluate($this->dropdownPlacement);
    }

    public function getColumns(): int
    {
        return (int) $this->evaluate($this->columns);
    }

    public function getMaxHeight(): string
    {
        return (string) $this->evaluate($this->maxHeight);
    }

    /**
     * Set the flag height class for modal flagsOnly cards.
     * Default: 'h-16'. Examples: 'h-12', 'h-20', 'h-24'.
     */
    public function flagHeight(string | Closure $height): static
    {
        $this->flagHeight = $height;

        return $this;
    }

    public function getFlagHeight(): string
    {
        return (string) $this->evaluate($this->flagHeight);
    }

    /**
     * Set the char avatar size class for modal cards.
     * Default: 'size-8'. Examples: 'size-10', 'size-12'.
     */
    public function charAvatarHeight(string | Closure $height): static
    {
        $this->charAvatarHeight = $height;

        return $this;
    }

    public function getCharAvatarHeight(): string
    {
        return (string) $this->evaluate($this->charAvatarHeight);
    }
}

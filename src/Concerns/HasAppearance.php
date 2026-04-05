<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Concerns;

use BezhanSalleh\LanguageSwitch\Enums\DisplayMode;
use BezhanSalleh\LanguageSwitch\Enums\TriggerStyle;
use Closure;
use Filament\Support\Icons\Heroicon;

trait HasAppearance
{
    protected bool | Closure $isCircular = false;

    protected bool | Closure $isInline = false;

    protected DisplayMode | Closure $displayMode = DisplayMode::Dropdown;

    protected string | Closure | null $dropdownPlacement = null;

    protected int | Closure $columns = 1;

    protected string | Closure $maxHeight = '24rem';

    protected string | Closure $flagHeight = 'h-16';

    protected string | Closure $avatarHeight = 'size-8';

    protected TriggerStyle | Closure | null $triggerStyle = null;

    protected string | Heroicon | Closure $triggerIcon = Heroicon::Language;

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

    public function displayMode(DisplayMode | Closure $mode): static
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
        return $this->evaluate($this->displayMode);
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
     * Set the avatar size class for modal cards.
     * Default: 'size-8'. Examples: 'size-10', 'size-12'.
     */
    public function avatarHeight(string | Closure $height): static
    {
        $this->avatarHeight = $height;

        return $this;
    }

    public function getAvatarHeight(): string
    {
        return (string) $this->evaluate($this->avatarHeight);
    }

    /**
     * Configure the trigger's style and/or icon in a single call.
     *
     * Examples:
     *   $switch->trigger(style: TriggerStyle::Flag);
     *   $switch->trigger(style: TriggerStyle::IconLabel, icon: Heroicon::GlobeAlt);
     *   $switch->trigger(icon: 'phosphor-translate');
     */
    public function trigger(
        TriggerStyle | Closure | null $style = null,
        string | Heroicon | Closure | null $icon = null,
    ): static {
        if ($style !== null) {
            $this->triggerStyle = $style;
        }

        if ($icon !== null) {
            $this->triggerIcon = $icon;
        }

        return $this;
    }

    public function getTriggerStyle(): TriggerStyle
    {
        $style = $this->evaluate($this->triggerStyle);

        if ($style instanceof TriggerStyle) {
            return $style;
        }

        $context = $this->getRenderContext();
        $hasFlags = filled($this->evaluate($this->flags));

        return match ($context) {
            'topbar' => $hasFlags ? TriggerStyle::Flag : TriggerStyle::Icon,
            default => TriggerStyle::IconLabel,
        };
    }

    public function getTriggerIcon(): string | Heroicon
    {
        return $this->evaluate($this->triggerIcon);
    }
}

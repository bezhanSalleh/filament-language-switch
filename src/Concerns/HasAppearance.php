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

    public function flagHeight(string | Closure $height): static
    {
        $this->flagHeight = $height;

        return $this;
    }

    public function getFlagHeight(): string
    {
        return (string) $this->evaluate($this->flagHeight);
    }

    public function avatarHeight(string | Closure $height): static
    {
        $this->avatarHeight = $height;

        return $this;
    }

    public function getAvatarHeight(): string
    {
        return (string) $this->evaluate($this->avatarHeight);
    }

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

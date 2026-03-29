<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Concerns;

use Closure;

trait HasModal
{
    protected string | Closure | null $modalHeading = null;

    protected string | Closure | null $modalWidth = null;

    protected bool | Closure $modalSlideOver = false;

    protected string | Closure | null $modalIcon = null;

    protected string | Closure | null $modalIconColor = null;

    public function modalHeading(string | Closure | null $heading): static
    {
        $this->modalHeading = $heading;

        return $this;
    }

    public function modalWidth(string | Closure | null $width): static
    {
        $this->modalWidth = $width;

        return $this;
    }

    public function modalSlideOver(bool | Closure $condition = true): static
    {
        $this->modalSlideOver = $condition;

        return $this;
    }

    public function modalIcon(string | Closure | null $icon): static
    {
        $this->modalIcon = $icon;

        return $this;
    }

    public function modalIconColor(string | Closure | null $color): static
    {
        $this->modalIconColor = $color;

        return $this;
    }

    public function getModalHeading(): ?string
    {
        return $this->evaluate($this->modalHeading) ?? __('language-switch::translations.modal_heading');
    }

    public function getModalWidth(): ?string
    {
        return $this->evaluate($this->modalWidth);
    }

    public function isModalSlideOver(): bool
    {
        return (bool) $this->evaluate($this->modalSlideOver);
    }

    public function getModalIcon(): ?string
    {
        return $this->evaluate($this->modalIcon);
    }

    public function getModalIconColor(): ?string
    {
        return $this->evaluate($this->modalIconColor);
    }
}

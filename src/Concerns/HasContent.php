<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Concerns;

use Closure;

trait HasContent
{
    protected string | Closure | null $contentView = null;

    protected string | Closure | null $itemView = null;

    /**
     * Set a custom Blade view for the locale list content.
     * The view receives $ls (LanguageSwitch instance) and all resolved config variables.
     */
    public function contentView(string | Closure | null $view): static
    {
        $this->contentView = $view;

        return $this;
    }

    /**
     * Set a custom Blade view for each locale item.
     * The view receives $locale, $label, $flag, $charAvatar, $isFlagsOnly, etc.
     */
    public function itemView(string | Closure | null $view): static
    {
        $this->itemView = $view;

        return $this;
    }

    public function getContentView(): ?string
    {
        return $this->evaluate($this->contentView);
    }

    public function getItemView(): ?string
    {
        return $this->evaluate($this->itemView);
    }
}

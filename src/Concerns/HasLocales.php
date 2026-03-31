<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Concerns;

use BezhanSalleh\LanguageSwitch\Events\LocaleChanged;
use Closure;
use Exception;

trait HasLocales
{
    protected string | Closure | null $displayLocale = null;

    protected array | Closure $locales = [];

    protected array | Closure $labels = [];

    protected array | Closure $flags = [];

    protected bool | Closure $isFlagsOnly = false;

    protected bool | Closure $nativeLabel = false;

    protected string | Closure | null $userPreferredLocale = null;

    public function locales(array | Closure $locales): static
    {
        $this->locales = $locales;

        return $this;
    }

    public function labels(array | Closure $labels): static
    {
        $this->labels = $labels;

        return $this;
    }

    public function displayLocale(string | Closure | null $locale = null): static
    {
        $this->displayLocale = $locale ?? app()->getLocale();

        return $this;
    }

    public function nativeLabel(bool | Closure $condition = true): static
    {
        $this->nativeLabel = $condition;

        return $this;
    }

    public function flags(array | Closure $flags): static
    {
        $this->flags = $flags;

        return $this;
    }

    public function flagsOnly(bool $condition = true): static
    {
        $this->isFlagsOnly = $condition;

        return $this;
    }

    public function userPreferredLocale(Closure | string | null $locale): static
    {
        $this->userPreferredLocale = $locale;

        return $this;
    }

    public function getLocales(): array
    {
        return (array) $this->evaluate($this->locales);
    }

    public function getLabels(): array
    {
        return (array) $this->evaluate($this->labels);
    }

    public function getDisplayLocale(): ?string
    {
        return $this->evaluate($this->displayLocale);
    }

    public function getNativeLabel(): bool
    {
        return (bool) $this->evaluate($this->nativeLabel);
    }

    /**
     * @throws Exception
     */
    public function getFlags(): array
    {
        $flagUrls = (array) $this->evaluate($this->flags);

        foreach ($flagUrls as $flagUrl) {
            if (! filter_var($flagUrl, FILTER_VALIDATE_URL)) {
                throw new Exception('Invalid flag url');
            }
        }

        return $flagUrls;
    }

    /**
     * @throws Exception
     */
    public function isFlagsOnly(): bool
    {
        return (bool) $this->evaluate($this->isFlagsOnly) && filled($this->getFlags());
    }

    public function getUserPreferredLocale(): ?string
    {
        return $this->evaluate($this->userPreferredLocale) ?? null;
    }

    public function getPreferredLocale(): string
    {
        $locale = session()->get('locale') ??
            request()->query('locale') ??
            request()->input('locale') ??
            request()->cookie('filament_language_switch_locale') ??
            $this->getUserPreferredLocale() ??
            config('app.locale') ??
            request()->getPreferredLanguage();

        return in_array($locale, $this->getLocales(), true) ? $locale : config('app.locale');
    }

    public function getLabel(string $locale): string
    {
        if (array_key_exists($locale, ($labels = $this->getLabels())) && ! $this->getNativeLabel()) {
            return strval($labels[$locale]);
        }

        return str(
            locale_get_display_name(
                locale: $locale,
                displayLocale: $this->getNativeLabel() ? $locale : $this->getDisplayLocale()
            )
        )
            ->title()
            ->toString();
    }

    public function getFlag(string $locale): string
    {
        return $this->getFlags()[$locale] ?? str($locale)->upper()->toString();
    }

    public function getCharAvatar(string $locale): string
    {
        return str($locale)->length() > 2
            ? str($locale)->substr(0, 2)->upper()->toString()
            : str($locale)->upper()->toString();
    }

    public static function trigger(string $locale): void
    {
        session()->put('locale', $locale);

        cookie()->queue(cookie()->forever('filament_language_switch_locale', $locale));

        event(new LocaleChanged($locale));
    }
}

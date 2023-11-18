<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentLanguageSwitch;

use Closure;
use Filament\Panel;
use Filament\Support\Concerns;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class LanguageSwitch
{
    use Concerns\Configurable;
    use Concerns\EvaluatesClosures;

    protected bool | Closure $isCircular = false;

    protected ?string $displayLocale = null;

    protected array | Closure $excludes = [];

    protected array | Closure $flags = [];

    protected bool $isFlagsOnly = false;

    protected array | Closure $labels = [];

    protected array | Closure $locales = [];

    protected Closure | string $renderHook = 'panels::global-search.after';

    public static function make(): static
    {
        $static = app(static::class);

        $static->displayLocale();

        $static->configure();

        return $static;
    }

    public static function boot(): void
    {
        $static = static::make();

        FilamentView::registerRenderHook(
            name: $static->getRenderHook(),
            hook: fn (): string => Blade::render('@livewire(\'filament-language-switch\')')
        );
    }

    public function circular(bool $condition = true): static
    {
        $this->isCircular = $condition;

        return $this;
    }

    public function displayLocale(string $locale = 'en'): static
    {
        $this->displayLocale = $locale;

        return $this;
    }

    public function excludes(array | Closure $excludes): static
    {
        $this->excludes = $excludes;

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

    public function labels(array | Closure $labels): static
    {
        $this->labels = $labels;

        return $this;
    }

    public function locales(array | Closure $locales): static
    {
        $this->locales = $locales;

        return $this;
    }

    public function renderHook(array | Closure $renderHook): static
    {
        $this->renderHook = $renderHook;

        return $this;
    }

    public function getDisplayLocale(): string
    {
        return (string) $this->evaluate($this->displayLocale);
    }

    public function getExcludes(): array
    {
        return (array) $this->evaluate($this->excludes);
    }

    public function getFlags(): array
    {
        $flagUrls = (array) $this->evaluate($this->flags);

        foreach ($flagUrls as $url) {
            if (! filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \Exception('Invlid flag url');
                exit;
            }
        }

        return $flagUrls;
    }

    public function isCircular(): bool
    {
        return (bool) $this->evaluate($this->isCircular);
    }

    public function isFlagsOnly(): bool
    {
        return (bool) $this->evaluate($this->isFlagsOnly) && filled($this->getFlags());
    }

    public function getLabels(): array
    {
        return (array) $this->evaluate($this->labels);
    }

    public function getLocales(): array
    {
        return (array) $this->evaluate(filled($this->locales) ? $this->locales : ['ar', config('app.locale'), 'fr']);
    }

    public function getRenderHook(): string
    {
        return (string) $this->evaluate($this->renderHook);
    }

    /**
     * @return array<string, Panel>
     */
    public function getPanels(): array
    {
        return collect(filament()->getPanels())
            ->reject(fn (Panel $panel) => in_array($panel->getId(), $this->getExcludes()))
            ->toArray();
    }

    public function getCurrentPanel(): Panel
    {
        return filament()->getCurrentPanel();
    }

    public function getFlag(string $locale): string
    {
        return $this->flags[$locale] ?? str($locale)->upper()->toString();
    }

    public function getLabel(string $locale): string
    {
        return $this->labels[$locale] ?? locale_get_display_name($locale, $this->getDisplayLocale());
    }
}

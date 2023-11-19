<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Enums\Alignment;
use Closure;
use Filament\Panel;
use Filament\Support\Concerns;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class LanguageSwitch
{
    use Concerns\Configurable;
    use Concerns\EvaluatesClosures;

    protected ?string $displayLocale = null;

    protected array | Closure | null $displayOn = null;

    protected array | Closure $excludes = [];

    protected array | Closure $flags = [];

    protected bool | Closure $isCircular = false;

    protected bool $isFlagsOnly = false;

    protected array | Closure $labels = [];

    protected array | Closure $locales = [];

    protected ?Alignment $placement = null;

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
            hook: fn (): string => Blade::render('<livewire:filament-language-switch key=\'fls-in-panels\' />')
        );

        if ($static->isDisplayOn()) {
            FilamentView::registerRenderHook(
                name: 'panels::body.end',
                hook: fn (): string => Blade::render('<livewire:filament-language-switch key=\'fls-outside-panels\' />')
            );
        }
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

    public function displayOn(array | Closure $routes = null): static
    {
        $this->displayOn = $routes ?? [
            'auth.login',
            'auth.password',
            'auth.profile',
            'auth.register',
        ];

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

    public function placement(Alignment $alignment): static
    {
        $this->placement = $alignment;

        return $this;
    }

    public function renderHook(string $hook): static
    {
        $this->renderHook = $hook;

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

    public function isDisplayOn(): bool
    {
        return str(request()->route()->getName())->contains($this->displayOn);
    }

    public function getLabels(): array
    {
        return (array) $this->evaluate($this->labels);
    }

    public function getLocales(): array
    {
        return (array) $this->evaluate(filled($this->locales) ? $this->locales : ['ar', config('app.locale'), 'fr']);
    }

    public function getPlacement(): Alignment
    {
        return $this->placement ?? Alignment::TopRight;
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

    public function generateId(): string
    {
        return str()->random(8);
    }
}

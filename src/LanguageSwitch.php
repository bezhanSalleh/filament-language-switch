<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use Closure;
use Exception;
use Filament\Panel;
use Filament\Support\Concerns;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class LanguageSwitch
{
    use Concerns\Configurable;
    use Concerns\EvaluatesClosures;

    protected ?string $displayLocale = null;

    protected array | Closure | null $outsidePanelRoutes = null;

    protected array | Closure $excludes = [];

    protected array | Closure $flags = [];

    protected bool | Closure $isCircular = false;

    protected bool $isFlagsOnly = false;

    protected array | Closure $labels = [];

    protected array | Closure $locales = [];

    protected ?Placement $outsidePanelPlacement = null;

    protected bool | Closure $visibleInsidePanels = false;

    protected bool | Closure $visibleOutsidePanels = false;

    protected Closure | string $renderHook = 'panels::global-search.after';

    public static function make(): static
    {
        $static = app(static::class);

        $static->visible();

        $static->displayLocale();

        $static->outsidePanelRoutes();

        $static->configure();

        return $static;
    }

    public static function boot(): void
    {
        $static = static::make();

        if ($static->isVisibleInsidePanels()) {
            FilamentView::registerRenderHook(
                name: $static->getRenderHook(),
                hook: fn (): string => Blade::render('<livewire:filament-language-switch key=\'fls-in-panels\' />')
            );
        }

        if ($static->isVisibleOutsidePanels()) {
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

    public function displayLocale(string $locale = null): static
    {
        $this->displayLocale = $locale ?? app()->getLocale();

        return $this;
    }

    public function outsidePanelRoutes(array | Closure $routes = null): static
    {
        $this->outsidePanelRoutes = $routes ?? [
            'auth.login',
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

    public function outsidePanelPlacement(Placement $placement): static
    {
        $this->outsidePanelPlacement = $placement;

        return $this;
    }

    public function renderHook(string $hook): static
    {
        $this->renderHook = $hook;

        return $this;
    }

    public function visible(bool | Closure $insidePanels = true, bool | Closure $outsidePanels = false): static
    {
        $this->visibleInsidePanels = $insidePanels;

        $this->visibleOutsidePanels = $outsidePanels;

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

    /**
     * @throws Exception
     */
    public function getFlags(): array
    {
        $flagUrls = (array) $this->evaluate($this->flags);

        foreach ($flagUrls as $url) {
            if (! filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \Exception('Invalid flag url');
                exit;
            }
        }

        return $flagUrls;
    }

    public function isCircular(): bool
    {
        return (bool) $this->evaluate($this->isCircular);
    }

    /**
     * @throws Exception
     */
    public function isFlagsOnly(): bool
    {
        return (bool) $this->evaluate($this->isFlagsOnly) && filled($this->getFlags());
    }

    public function isVisibleInsidePanels(): bool
    {
        return (bool) ($this->evaluate($this->visibleInsidePanels)
            && count($this->locales) > 1
            && $this->isCurrentPanelIncluded());
    }

    public function isVisibleOutsidePanels(): bool
    {
        return (bool) ($this->evaluate($this->visibleOutsidePanels)
            && str(request()->route()->getName())->contains($this->outsidePanelRoutes)
            && $this->isCurrentPanelIncluded());
    }

    public function getLabels(): array
    {
        return (array) $this->evaluate($this->labels);
    }

    public function getLocales(): array
    {
        return (array) $this->evaluate($this->locales);
    }

    public function getOutsidePanelPlacement(): Placement
    {
        return $this->outsidePanelPlacement ?? Placement::TopRight;
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
        return $this->labels[$locale] ?? str(locale_get_display_name($locale, $this->getDisplayLocale()))
            ->title()
            ->toString();
    }

    public function isCurrentPanelIncluded(): bool
    {
        return array_key_exists($this->getCurrentPanel()->getId(), $this->getPanels());
    }

    public function getCharAvatar(string $locale): string
    {
        return str($locale)->length() > 2
            ? str($locale)->substr(0, 2)->upper()->toString()
            : str($locale)->upper()->toString();
    }
}

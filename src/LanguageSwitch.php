<?php

declare(strict_types=1);

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use BezhanSalleh\FilamentLanguageSwitch\Events\LocaleChanged;
use Closure;
use Exception;
use Filament\Panel;
use Filament\Support\Components\Component;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;

class LanguageSwitch extends Component
{
    protected ?string $displayLocale = null;

    protected array | Closure | null $outsidePanelRoutes = null;

    protected array | Closure $excludes = [];

    protected array | Closure $flags = [];

    protected bool | Closure $isCircular = false;

    protected bool $isFlagsOnly = false;

    protected array | Closure $labels = [];

    protected array | Closure $locales = [];

    protected bool $nativeLabel = false;

    protected ?Placement $outsidePanelPlacement = null;

    protected bool | Closure $visibleInsidePanels = false;

    protected bool | Closure $visibleOutsidePanels = false;

    protected string $maxHeight = 'max-content';

    protected Closure | string $renderHook = 'panels::global-search.after';

    protected Closure | string | null $userPreferredLocale = null;

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
                name: 'panels::body.start',
                hook: fn (): string => Blade::render('<livewire:filament-language-switch key=\'fls-outside-panels\' />')
            );
        }
    }

    public function circular(bool $condition = true): static
    {
        $this->isCircular = $condition;

        return $this;
    }

    public function displayLocale(?string $locale = null): static
    {
        $this->displayLocale = $locale ?? app()->getLocale();

        return $this;
    }

    public function nativeLabel(bool $condition = true): static
    {
        $this->nativeLabel = $condition;

        return $this;
    }

    public function outsidePanelRoutes(array | Closure | null $routes = null): static
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

    public function userPreferredLocale(Closure | string | null $locale): static
    {
        $this->userPreferredLocale = $locale;

        return $this;
    }

    public function visible(bool | Closure $insidePanels = true, bool | Closure $outsidePanels = false): static
    {
        $this->visibleInsidePanels = $insidePanels;

        $this->visibleOutsidePanels = $outsidePanels;

        return $this;
    }

    public function maxHeight(string $height): static
    {
        $this->maxHeight = $height;

        return $this;
    }

    public function getDisplayLocale(): ?string
    {
        return $this->evaluate($this->displayLocale);
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
            && str(request()->route()?->getName())->contains($this->outsidePanelRoutes)
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

    public function getNativeLabel(): bool
    {
        return (bool) $this->evaluate($this->nativeLabel);
    }

    public function getOutsidePanelPlacement(): Placement
    {
        return $this->outsidePanelPlacement ?? Placement::TopRight;
    }

    public function getRenderHook(): string
    {
        return (string) $this->evaluate($this->renderHook);
    }

    public function getUserPreferredLocale(): ?string
    {
        return $this->evaluate($this->userPreferredLocale) ?? null;
    }

    public function getPreferredLocale(): string
    {
        $locale = session()->get('locale') ??
            request()->get('locale') ??
            request()->cookie('filament_language_switch_locale') ??
            $this->getUserPreferredLocale() ??
            config('app.locale', 'en') ??
            request()->getPreferredLanguage();

        return in_array($locale, $this->getLocales(), true) ? $locale : config('app.locale');
    }

    public function getMaxHeight(): string
    {
        return $this->maxHeight;
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
        if (array_key_exists($locale, $this->labels) && ! $this->getNativeLabel()) {
            return strval($this->labels[$locale]);
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

    public static function trigger(string $locale)
    {
        session()->put('locale', $locale);

        cookie()->queue(cookie()->forever('filament_language_switch_locale', $locale));

        event(new LocaleChanged($locale));

        return redirect(request()->header('Referer'));
    }
}

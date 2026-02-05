<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch;

use BezhanSalleh\LanguageSwitch\Enums\Placement;
use BezhanSalleh\LanguageSwitch\Events\LocaleChanged;
use Closure;
use Exception;
use Filament\Panel;
use Filament\Support\Components\Component;
use Filament\Support\Facades\FilamentView;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Blade;

class LanguageSwitch extends Component
{
    protected string | Closure | null $displayLocale = null;

    protected array | Closure | null $outsidePanelRoutes = null;

    protected array | Closure $excludes = [];

    protected array | Closure $flags = [];

    protected bool | Closure $isCircular = false;

    protected bool | Closure $isFlagsOnly = false;

    protected array | Closure $labels = [];

    protected array | Closure $locales = [];

    protected bool | Closure $nativeLabel = false;

    /** @var Placement|Closure<string|Placement|null>|null */
    protected string | Closure | Placement | null $outsidePanelPlacement = Placement::TopRight;

    protected bool | Closure $visibleInsidePanels = false;

    protected bool | Closure $visibleOutsidePanels = false;

    protected string | Closure $maxHeight = 'max-content';

    protected string | Closure $renderHook = 'panels::global-search.after';

    protected string | Closure | null $userPreferredLocale = null;

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
                hook: fn (): string => Blade::render("<livewire:language-switch-component key='fls-in-panels' />")
            );
        }

        if ($static->isVisibleOutsidePanels()) {
            FilamentView::registerRenderHook(
                name: 'panels::body.start',
                hook: fn (): string => Blade::render("<livewire:language-switch-component key='fls-outside-panels' />")
            );
        }
    }

    public function circular(bool | Closure $condition = true): static
    {
        $this->isCircular = $condition;

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

    public function renderHook(Closure | string $hook): static
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

    public function maxHeight(Closure | string $height): static
    {
        $this->maxHeight = $height;

        return $this;
    }

    public function isCircular(): bool
    {
        return (bool) $this->evaluate($this->isCircular);
    }

    public function getDisplayLocale(): ?string
    {
        return $this->evaluate($this->displayLocale);
    }

    public function getNativeLabel(): bool
    {
        return (bool) $this->evaluate($this->nativeLabel);
    }

    public function getOutsidePanelRoutes(): array
    {
        return (array) $this->evaluate($this->outsidePanelRoutes);
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

        foreach ($flagUrls as $flagUrl) {
            if (! filter_var($flagUrl, FILTER_VALIDATE_URL)) {
                throw new \Exception('Invalid flag url');
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
        $outsidePanelPlacement = $this->evaluate($this->outsidePanelPlacement);

        return match (true) {
            $outsidePanelPlacement instanceof Placement => $outsidePanelPlacement,
            is_string($outsidePanelPlacement) => Placement::tryFrom($outsidePanelPlacement) ?? Placement::TopRight,
            default => Placement::TopRight
        };
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

    public function isVisibleInsidePanels(): bool
    {
        return $this->evaluate($this->visibleInsidePanels)
            && count($this->getLocales()) > 1
            && $this->isCurrentPanelIncluded();
    }

    public function isVisibleOutsidePanels(): bool
    {
        return $this->evaluate($this->visibleOutsidePanels)
            && str(request()->route()?->getName())->contains($this->getOutsidePanelRoutes())
            && $this->isCurrentPanelIncluded();
    }

    public function getMaxHeight(): string
    {
        return (string) $this->evaluate($this->maxHeight);
    }

    /**
     * @return array<string, Panel>
     */
    public function getPanels(): array
    {
        return collect(filament()->getPanels())
            ->reject(fn (Panel $panel): bool => in_array($panel->getId(), $this->getExcludes()))
            ->toArray();
    }

    public function getCurrentPanel(): Panel
    {
        return filament()->getCurrentOrDefaultPanel();
    }

    public function getFlag(string $locale): string
    {
        return $this->getFlags()[$locale] ?? str($locale)->upper()->toString();
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

    public static function trigger(string $locale): Redirector | RedirectResponse
    {
        session()->put('locale', $locale);

        cookie()->queue(cookie()->forever('filament_language_switch_locale', $locale));

        event(new LocaleChanged($locale));

        return redirect(request()->header('Referer'));
    }
}

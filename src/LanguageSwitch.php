<?php

namespace BezhanSalleh\LanguageSwitch;

use BackedEnum;
use BezhanSalleh\LanguageSwitch\Enums\Placement;
use BezhanSalleh\LanguageSwitch\Events\LocaleChanged;
use Closure;
use Exception;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\Support\Components\Component;
use Filament\Support\Enums\Alignment;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Blade;

class LanguageSwitch extends Component
{
    // --- 1. CORE CONFIGURATION ---
    protected string | Closure | null $displayLocale = null;
    protected array | Closure $locales = [];
    protected array | Closure $labels = [];
    protected bool | Closure $nativeLabel = false;
    protected string | Closure | null $userPreferredLocale = null;
    protected array | Closure $suggested = [];

    // --- 2. VISIBILITY & PLACEMENT ---
    protected bool | Closure $visibleInsidePanels = false;
    protected bool | Closure $visibleOutsidePanels = false;
    protected array | Closure $outsidePanelRoutes = [];
    protected array | Closure $excludes = [];
    protected string | Closure | Placement | null $outsidePanelPlacement = Placement::TopRight;
    protected string | Closure | null $outsidePanelBeforeSpace = null;
    protected string | Closure | null $outsidePanelAfterSpace = null;
    protected string | Closure $renderHook = 'panels::global-search.after';
    protected bool | Closure $renderAsUserMenuItem = false;
    protected int | Closure $userMenuSort = 10;
    protected bool | Closure $isFixedOutsidePanels = false;

    // --- 3. TRIGGER BUTTON STYLING ---
    protected string | Closure $buttonStyle = 'default';
    protected string | Closure | null $icon = null;
    protected string | Closure $iconSize = 'w-5 h-5';
    protected string | Closure $iconPosition = 'before'; // before, after
    protected string | Closure $flagSize = 'w-7 h-7';
    protected string | Closure $flagPosition = 'before'; // before, after
    protected string | Closure | null $triggerClass = null;
    protected string | Closure | null $wrapperClass = null;
    protected bool | Closure $isCircular = false;
    protected string | Closure $displayFormat = 'code'; // full, code, none
    protected bool | Closure $hideLanguageCodeOutsideModal = false;

    // --- 4. DROPDOWN / LIST STYLING ---
    protected string | Closure $displayAs = 'dropdown';
    protected string | Closure $itemStyle = 'list';
    protected string | Closure | null $itemClass = null;
    protected array | Closure $flags = [];
    protected bool | Closure $isFlagsOnly = false;
    protected string | Closure $maxHeight = 'max-content';
    protected string | Closure $languageCodeStyle = 'default';
    protected bool | Closure $hideLanguageCodeInsideModal = false;
    protected int | Closure $gridColumns = 1;
    protected string | Closure | null $dropdownWidth = null;

    // --- 5. MODAL CONFIGURATION ---
    protected string | Closure | null $modalHeading = null;
    protected string | Closure | null $modalDescription = null;
    protected string | Closure | null $modalWidth = null;
    protected bool | Closure $modalSlideOver = false;
    protected int | Closure $modalGridColumns = 1;
    protected Alignment | string | Closure | null $modalAlignment = null;
    protected bool | Closure | null $modalCloseButton = null;
    protected bool | Closure | null $modalAutofocus = null;
    protected string | BackedEnum | Htmlable | Closure | null $modalIcon = null;
    protected string | array | Closure | null $modalIconColor = null;
    protected bool | Closure | null $closeModalByClickingAway = null;
    protected bool | Closure | null $closeModalByEscaping = null;
    protected string | Closure | null $modalClass = null;

    // --- 6. CONTENT INJECTION ---
    protected string | Htmlable | View | Closure | null $beforeCoreContent = null;
    protected string | Closure | null $beforeCoreContentClasses = null;
    protected string | Htmlable | View | Closure | null $afterCoreContent = null;
    protected string | Closure | null $afterCoreContentClasses = null;

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
            if ($static->isRenderedAsUserMenuItem()) {
                $panel = filament()->getCurrentOrDefaultPanel();

                $panel->userMenuItems([
                    'language-switch' => MenuItem::make()
                        ->label(fn () => $static->getLabel(app()->getLocale()))
                        ->icon(fn () => $static->getIcon() ?? 'heroicon-m-language')
                        ->sort(fn () => $static->getUserMenuSort())
                        ->url('#fls-modal'),
                ]);

                FilamentView::registerRenderHook(
                    name: 'panels::body.end',
                    hook: fn (): string => Blade::render("<livewire:language-switch-component key='fls-in-panels-modal' />")
                );
            } else {
                FilamentView::registerRenderHook(
                    name: $static->getRenderHook(),
                    hook: fn (): string => Blade::render("<livewire:language-switch-component key='fls-in-panels' />")
                );
            }
        }

        if ($static->isVisibleOutsidePanels()) {
            // Render at body start (outside panels) - documented render hook behavior in Filament. :contentReference[oaicite:2]{index=2}
            FilamentView::registerRenderHook(
                name: 'panels::body.start',
                hook: fn (): string => Blade::render("<livewire:language-switch-component key='fls-outside-panels' />")
            );
        }
    }

    // --- Fluent Setters ---
    public function locales(array | Closure $locales): static { $this->locales = $locales; return $this; }
    public function labels(array | Closure $labels): static { $this->labels = $labels; return $this; }
    public function nativeLabel(bool | Closure $condition = true): static { $this->nativeLabel = $condition; return $this; }
    public function displayLocale(string | Closure | null $locale = null): static { $this->displayLocale = $locale ?? app()->getLocale(); return $this; }
    public function suggested(array | Closure $suggested): static { $this->suggested = $suggested; return $this; }
    public function visible(bool | Closure $insidePanels = true, bool | Closure $outsidePanels = false): static { $this->visibleInsidePanels = $insidePanels; $this->visibleOutsidePanels = $outsidePanels; return $this; }
    public function outsidePanelRoutes(array | Closure | null $routes = null): static { $this->outsidePanelRoutes = $routes ?? ['auth.login', 'auth.profile', 'auth.register']; return $this; }
    public function excludes(array | Closure $excludes): static { $this->excludes = $excludes; return $this; }
    public function outsidePanelPlacement(Placement | string | Closure $placement): static { $this->outsidePanelPlacement = $placement; return $this; }
    public function beforeSpace(string | Closure | null $space): static { $this->outsidePanelBeforeSpace = $space; return $this; }
    public function afterSpace(string | Closure | null $space): static { $this->outsidePanelAfterSpace = $space; return $this; }
    public function renderHook(string | Closure $hook): static { $this->renderHook = $hook; return $this; }
    public function userPreferredLocale(string | Closure | null $locale): static { $this->userPreferredLocale = $locale; return $this; }
    public function renderAsUserMenuItem(bool | Closure $condition = true): static { $this->renderAsUserMenuItem = $condition; return $this; }
    public function userMenuSort(int | Closure $sort): static { $this->userMenuSort = $sort; return $this; }
    public function isFixed(bool | Closure $condition = true): static { $this->isFixedOutsidePanels = $condition; return $this; }
    public function buttonStyle(string | Closure $style): static { $this->buttonStyle = $style; return $this; }
    public function icon(string | Closure | null $icon): static { $this->icon = $icon; return $this; }
    public function iconSize(string | Closure $size): static { $this->iconSize = $size; return $this; }
    public function iconPosition(string | Closure $position): static { $this->iconPosition = $position; return $this; }
    public function flagSize(string | Closure $size): static { $this->flagSize = $size; return $this; }
    public function flagPosition(string | Closure $position): static { $this->flagPosition = $position; return $this; }
    public function triggerClass(string | Closure | null $class): static { $this->triggerClass = $class; return $this; }
    public function wrapperClass(string | Closure | null $class): static { $this->wrapperClass = $class; return $this; }
    public function circular(bool | Closure $condition = true): static { $this->isCircular = $condition; return $this; }
    public function displayFormat(string | Closure $format): static { $this->displayFormat = $format; return $this; }
    public function displayAs(string | Closure $displayAs): static { $this->displayAs = $displayAs; return $this; }
    public function itemStyle(string | Closure $style): static { $this->itemStyle = $style; return $this; }
    public function itemClass(string | Closure | null $class): static { $this->itemClass = $class; return $this; }
    public function gridColumns(int | Closure $columns): static { $this->gridColumns = $columns; return $this; }
    public function dropdownWidth(string | Closure | null $width): static { $this->dropdownWidth = $width; return $this; }
    public function flags(array | Closure $flags): static { $this->flags = $flags; return $this; }
    public function flagsOnly(bool $condition = true): static { $this->isFlagsOnly = $condition; return $this; }
    public function maxHeight(string | Closure $height): static { $this->maxHeight = $height; return $this; }
    public function languageCodeStyle(string | Closure $style): static { $this->languageCodeStyle = $style; return $this; }
    public function hideLanguageCode(bool | Closure $insideModal = true, bool | Closure $outsideModal = false): static { $this->hideLanguageCodeInsideModal = $insideModal; $this->hideLanguageCodeOutsideModal = $outsideModal; return $this; }
    public function modalHeading(string | Closure | null $heading): static { $this->modalHeading = $heading; return $this; }
    public function modalDescription(string | Closure | null $description): static { $this->modalDescription = $description; return $this; }
    public function modalWidth(string | Closure | null $width): static { $this->modalWidth = $width; return $this; }
    public function modalSlideOver(bool | Closure $condition = true): static { $this->modalSlideOver = $condition; return $this; }
    public function modalGridColumns(int | Closure $columns): static { $this->modalGridColumns = $columns; return $this; }
    public function modalAlignment(Alignment | string | Closure | null $alignment): static { $this->modalAlignment = $alignment; return $this; }
    public function modalCloseButton(bool | Closure $condition = true): static { $this->modalCloseButton = $condition; return $this; }
    public function modalAutofocus(bool | Closure $condition = true): static { $this->modalAutofocus = $condition; return $this; }
    public function modalIcon(string | BackedEnum | Htmlable | Closure | null $icon): static { $this->modalIcon = $icon; return $this; }
    public function modalIconColor(string | array | Closure | null $color): static { $this->modalIconColor = $color; return $this; }
    public function closeModalByClickingAway(bool | Closure $condition = true): static { $this->closeModalByClickingAway = $condition; return $this; }
    public function closeModalByEscaping(bool | Closure $condition = true): static { $this->closeModalByEscaping = $condition; return $this; }
    public function modalClass(string | Closure | null $class): static { $this->modalClass = $class; return $this; }
    public function beforeCoreContent(string | Htmlable | View | Closure | null $content): static { $this->beforeCoreContent = $content; return $this; }
    public function beforeCoreContentClasses(string | Closure | null $classes): static { $this->beforeCoreContentClasses = $classes; return $this; }
    public function afterCoreContent(string | Htmlable | View | Closure | null $content): static { $this->afterCoreContent = $content; return $this; }
    public function afterCoreContentClasses(string | Closure | null $classes): static { $this->afterCoreContentClasses = $classes; return $this; }

    // --- Getters ---
    public function getLocales(): array { return (array) $this->evaluate($this->locales); }
    public function getLabels(): array { return (array) $this->evaluate($this->labels); }
    public function getNativeLabel(): bool { return (bool) $this->evaluate($this->nativeLabel); }
    public function getDisplayLocale(): ?string { return $this->evaluate($this->displayLocale); }
    public function getSuggested(): array { return (array) $this->evaluate($this->suggested); }
    public function isVisibleInsidePanels(): bool { return $this->evaluate($this->visibleInsidePanels) && count($this->getLocales()) > 1 && $this->isCurrentPanelIncluded(); }
    public function isVisibleOutsidePanels(): bool { return $this->evaluate($this->visibleOutsidePanels) && str(request()->route()?->getName())->contains($this->getOutsidePanelRoutes()) && $this->isCurrentPanelIncluded(); }
    public function isFixedOutsidePanels(): bool { return (bool) $this->evaluate($this->isFixedOutsidePanels); }
    public function getOutsidePanelRoutes(): array { return (array) $this->evaluate($this->outsidePanelRoutes); }
    public function getExcludes(): array { return (array) $this->evaluate($this->excludes); }
    public function getOutsidePanelPlacement(): Placement { $outsidePanelPlacement = $this->evaluate($this->outsidePanelPlacement); return match (true) { $outsidePanelPlacement instanceof Placement => $outsidePanelPlacement, is_string($outsidePanelPlacement) => Placement::tryFrom($outsidePanelPlacement) ?? Placement::TopRight, default => Placement::TopRight }; }
    public function getOutsidePanelBeforeSpace(): ?string { return $this->evaluate($this->outsidePanelBeforeSpace); }
    public function getOutsidePanelAfterSpace(): ?string { return $this->evaluate($this->outsidePanelAfterSpace); }
    public function getRenderHook(): string { return (string) $this->evaluate($this->renderHook); }
    public function isRenderedAsUserMenuItem(): bool { return (bool) $this->evaluate($this->renderAsUserMenuItem); }
    public function getUserMenuSort(): int { return (int) $this->evaluate($this->userMenuSort); }
    public function getButtonStyle(): string { return (string) $this->evaluate($this->buttonStyle); }
    public function getIcon(): ?string { return $this->evaluate($this->icon); }
    public function getIconSize(): string { return (string) $this->evaluate($this->iconSize); }
    public function getIconPosition(): string { return (string) $this->evaluate($this->iconPosition); }
    public function getFlagSize(): string { return (string) $this->evaluate($this->flagSize); }
    public function getFlagPosition(): string { return (string) $this->evaluate($this->flagPosition); }
    public function getTriggerClass(): ?string { return $this->evaluate($this->triggerClass); }
    public function getWrapperClass(): ?string { return $this->evaluate($this->wrapperClass); }
    public function isCircular(): bool { return (bool) $this->evaluate($this->isCircular); }
    public function getUserPreferredLocale(): ?string { return $this->evaluate($this->userPreferredLocale); }
    public function getDisplayFormat(): string { return (string) $this->evaluate($this->displayFormat); }
    public function getDisplayAs(): string { return (string) $this->evaluate($this->displayAs); }
    public function getItemStyle(): string { return (string) $this->evaluate($this->itemStyle); }
    public function getItemClass(): ?string { return $this->evaluate($this->itemClass); }
    public function getFlags(): array { $flagUrls = (array) $this->evaluate($this->flags); foreach ($flagUrls as $flagUrl) { if (! filter_var($flagUrl, FILTER_VALIDATE_URL)) { throw new Exception('Invalid flag url'); } } return $flagUrls; }
    public function isFlagsOnly(): bool { return (bool) $this->evaluate($this->isFlagsOnly) && filled($this->getFlags()); }
    public function getMaxHeight(): string { return (string) $this->evaluate($this->maxHeight); }
    public function getLanguageCodeStyle(): string { return (string) $this->evaluate($this->languageCodeStyle); }
    public function isLanguageCodeHiddenInsideModal(): bool { return (bool) $this->evaluate($this->hideLanguageCodeInsideModal); }
    public function isLanguageCodeHiddenOutsideModal(): bool { return (bool) $this->evaluate($this->hideLanguageCodeOutsideModal); }
    public function getGridColumns(): int { return (int) $this->evaluate($this->gridColumns); }
    public function getDropdownWidth(): ?string { return $this->evaluate($this->dropdownWidth); }
    public function getModalHeading(): ?string { return $this->evaluate($this->modalHeading) ?? __('language-switch::translations.modal_heading'); }
    public function getModalDescription(): ?string { return $this->evaluate($this->modalDescription); }
    public function getModalWidth(): ?string { return $this->evaluate($this->modalWidth); }
    public function isModalSlideOver(): bool { return (bool) $this->evaluate($this->modalSlideOver); }
    public function getModalGridColumns(): int { return (int) $this->evaluate($this->modalGridColumns); }
    public function getModalAlignment(): Alignment | string | null { return $this->evaluate($this->modalAlignment); }
    public function hasModalCloseButton(): ?bool { return $this->evaluate($this->modalCloseButton); }
    public function isModalAutofocused(): ?bool { return $this->evaluate($this->modalAutofocus); }
    public function getModalIcon(): string | BackedEnum | Htmlable | null { return $this->evaluate($this->modalIcon); }
    public function getModalIconColor(): string | array | null { return $this->evaluate($this->modalIconColor); }
    public function isModalClosedByClickingAway(): ?bool { return $this->evaluate($this->closeModalByClickingAway); }
    public function isModalClosedByEscaping(): ?bool { return $this->evaluate($this->closeModalByEscaping); }
    public function getModalClass(): ?string { return $this->evaluate($this->modalClass); }
    public function getBeforeCoreContent(): string | Htmlable | View | null { return $this->evaluate($this->beforeCoreContent); }
    public function getBeforeCoreContentClasses(): ?string { return $this->evaluate($this->beforeCoreContentClasses); }
    public function getAfterCoreContent(): string | Htmlable | View | null { return $this->evaluate($this->afterCoreContent); }
    public function getAfterCoreContentClasses(): ?string { return $this->evaluate($this->afterCoreContentClasses); }

    // --- Helpers ---
    public function getPreferredLocale(): string { $locale = session()->get('locale') ?? request()->get('locale') ?? request()->cookie('filament_language_switch_locale') ?? $this->getUserPreferredLocale() ?? config('app.locale', 'en') ?? request()->getPreferredLanguage(); return in_array($locale, $this->getLocales(), true) ? $locale : config('app.locale'); }
    public function getPanels(): array { return collect(filament()->getPanels())->reject(fn (Panel $panel): bool => in_array($panel->getId(), $this->getExcludes()))->toArray(); }
    public function getCurrentPanel(): ?Panel { return filament()->getCurrentOrDefaultPanel(); }
    public function getFlag(string $locale): string { return $this->getFlags()[$locale] ?? str($locale)->upper()->toString(); }
    public function getLabel(string $locale): string { if (array_key_exists($locale, ($labels = $this->getLabels())) && ! $this->getNativeLabel()) { return strval($labels[$locale]); } return str( locale_get_display_name( locale: $locale, displayLocale: $this->getNativeLabel() ? $locale : $this->getDisplayLocale() ) )->title()->toString(); }
    public function isCurrentPanelIncluded(): bool { $panel = $this->getCurrentPanel(); return $panel ? array_key_exists($panel->getId(), $this->getPanels()) : false; }
    public function getCharAvatar(string $locale): string { return str($locale)->length() > 2 ? str($locale)->substr(0, 2)->upper()->toString() : str($locale)->upper()->toString(); }
    public static function trigger(string $locale): Redirector | RedirectResponse { session()->put('locale', $locale); cookie()->queue(cookie()->forever('filament_language_switch_locale', $locale)); event(new LocaleChanged($locale)); return redirect(request()->header('Referer')); }
}

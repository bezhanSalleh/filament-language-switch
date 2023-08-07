<?php

namespace BezhanSalleh\FilamentLanguageSwitch;

use BezhanSalleh\FilamentLanguageSwitch\Http\Livewire\SwitchFilamentLanguage;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\Support\Concerns\Configurable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

class FilamentLanguageSwitchPlugin implements Plugin
{
    use Configurable;

    protected string $renderHookName = 'panels::global-search.after';

    protected ?bool $native = null;

    protected ?bool $flag = null;

    protected ?array $locales = null;

    public static function make(): static
    {
        $static = app(static::class);
        $static->configure();

        return $static;
    }

    public function renderHookName(string $hookName): static
    {
        $this->renderHookName = $hookName;

        return $this;
    }

    public function getRenderHookName(): string
    {
        return $this->renderHookName;
    }

    public static function get(): static
    {
        return filament(app(static::class)->getId());
    }

    public function getId(): string
    {
        return 'filament-language-switch';
    }

    public function register(Panel $panel): void
    {
        Livewire::component('switch-filament-language', SwitchFilamentLanguage::class);

        $panel
            ->renderHook(
                name: $this->getRenderHookName(),
                hook: fn (): string => Blade::render('@livewire(\'switch-filament-language\')')
            );
    }

    public function shouldShowSelector(): bool
    {
        $locales = $this->getLocales();

        return count($locales) > 1 || ! array_key_exists(app()->currentLocale(), $locales);
    }

    public function native(bool $enabled = true): self
    {
        $this->native = $enabled;

        return $this;
    }

    public function shouldShowNative(): bool
    {
        return is_null($this->native) ? config('filament-language-switch.native') : $this->native;
    }

    public function flag(bool $enabled = true): self
    {
        $this->flag = $enabled;

        return $this;
    }

    public function shouldShowFlag(): bool
    {
        return is_null($this->flag) ? config('filament-language-switch.flag') : $this->flag;
    }

    public function locales(array $allowedLocales): self
    {
        $this->locales = $allowedLocales;

        return $this;
    }

    public function getLocales(): array
    {
        $locales = config('filament-language-switch.locales');

        if (! is_null($this->locales)) {
            $locales = Arr::only($locales, $this->locales);
        }
        return $locales;
    }

    public function boot(Panel $panel): void
    {
        //
    }
}

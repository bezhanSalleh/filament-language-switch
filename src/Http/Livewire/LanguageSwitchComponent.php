<?php

namespace BezhanSalleh\LanguageSwitch\Http\Livewire;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class LanguageSwitchComponent extends Component
{
    #[On('language-switched')]
    public function changeLocale(string $locale): void
    {
        LanguageSwitch::trigger(locale: $locale);
    }

    public function render(): View
    {
        return view('language-switch::language-switch');
    }
}

<?php

namespace BezhanSalleh\FilamentLanguageSwitch\Http\Livewire;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class FilamentLanguageSwitch extends Component
{
    #[On('language-switched')]
    public function changeLocale($locale)
    {
        LanguageSwitch::trigger(locale: $locale);
    }

    public function render(): View
    {
        return view('filament-language-switch::language-switch');
    }
}

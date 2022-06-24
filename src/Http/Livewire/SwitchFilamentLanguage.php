<?php

namespace BezhanSalleh\FilamentLanguageSwitch\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class SwitchFilamentLanguage extends Component
{
    public function changeLocale($locale)
    {
        session()->put('locale', $locale);

        $this->redirect(request()->header('Referer'));
    }

    public function render(): View
    {
        return view('filament-language-switch::language-switch');
    }
}

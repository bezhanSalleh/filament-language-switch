<?php

namespace BezhanSalleh\FilamentLanguageSwitch\Http\Middleware;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Closure;
use Illuminate\Http\Request;

class SwitchLanguageLocale
{
    public function handle(Request $request, Closure $next): \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
    {
        $locale = session()->get('locale')
            ?? $request->get('locale')
            ?? $request->cookie('filament_language_switch_locale')
            ?? $this->getBrowserLocale($request)
            ?? config('app.locale', 'en');

        if (in_array($locale, LanguageSwitch::make()->getLocales())) {
            app()->setLocale($locale);
        }

        return $next($request);
    }

    private function getBrowserLocale(Request $request): ?string
    {
        $userLangs = preg_split('/[,;]/', $request->server('HTTP_ACCEPT_LANGUAGE'));

        foreach ($userLangs as $locale) {
            return in_array($locale, LanguageSwitch::make()->getLocales())
                ? $locale
                : null;
        }
    }
}

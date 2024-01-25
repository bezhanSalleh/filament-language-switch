<?php

namespace BezhanSalleh\FilamentLanguageSwitch\Http\Middleware;

use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Closure;
use Illuminate\Http\Request;

class SwitchLanguageLocale
{
    public function handle(Request $request, Closure $next): mixed
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
        $appLocales = LanguageSwitch::make()->getLocales();

        $userLocales = preg_split('/[,;]/', $request->server('HTTP_ACCEPT_LANGUAGE'));

        foreach ($userLocales as $locale) {
            if (in_array($locale, $appLocales)) {
                return $locale;
            }
        }

        return null;
    }
}

<?php

namespace BezhanSalleh\FilamentLanguageSwitch\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SwitchLanguageLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = session()->get('locale') ??
        $request->get('locale') ??
        $request->cookie('filament_language_switch_locale') ??
        $this->getBrowserLocale($request) ??
        config('app.locale', 'en');

        if (array_key_exists($locale, config('filament-language-switch.locales'))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }

    /**
     * Determine the locale of the user's browser.
     *
     * @return ?string
     */
    private function getBrowserLocale(Request $request): ?string
    {
        $userLangs = preg_split('/[,;]/', $request->server('HTTP_ACCEPT_LANGUAGE'));
        foreach ($userLangs as $locale) {
            if (Arr::exists(config('filament-language-switch.locales'), $locale)) {
                return $locale;
            }
        }

        return null;
    }
}

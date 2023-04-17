<?php

namespace BezhanSalleh\FilamentLanguageSwitch\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SwitchLanguageLocale
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = session()->get('locale') ?? $request->get('locale') ?? $request->cookie('filament_language_switch_locale') ?? config('app.locale', 'en');

        if (array_key_exists($locale, config('filament-language-switch.locales'))) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}

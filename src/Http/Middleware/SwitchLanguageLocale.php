<?php

namespace BezhanSalleh\FilamentLanguageSwitch\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class SwitchLanguageLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale')) {
            app()->setLocale(session()->get('locale'));

            if (config('filament-language-switch::carbon')) {
                Carbon::setLocale(session()->get('locale'));
            }
        }

        return $next($request);
    }
}

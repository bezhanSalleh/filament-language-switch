<?php

namespace BezhanSalleh\LanguageSwitch\Http\Middleware;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Closure;
use Illuminate\Http\Request;

class SwitchLanguageLocale
{
    public function handle(Request $request, Closure $next): mixed
    {
        app()->setLocale(
            locale: LanguageSwitch::make()->getPreferredLocale()
        );

        return $next($request);
    }
}

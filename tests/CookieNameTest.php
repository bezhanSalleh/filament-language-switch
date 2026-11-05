<?php

declare(strict_types=1);

use BezhanSalleh\LanguageSwitch\LanguageSwitch;

it('falls back to the original cookie name', function () {
    expect(LanguageSwitch::make()->getCookieName())
        ->toBe('filament_language_switch_locale');
});

it('can override the cookie name', function () {
    expect(LanguageSwitch::make()->cookieName('my_app_locale')->getCookieName())
        ->toBe('my_app_locale');
});

it('evaluates a closure cookie name', function () {
    expect(LanguageSwitch::make()->cookieName(fn (): string => 'closure_locale')->getCookieName())
        ->toBe('closure_locale');
});

it('applies the cookie name configured via configureUsing', function () {
    LanguageSwitch::configureUsing(fn (LanguageSwitch $switch) => $switch->cookieName('configured_locale'));

    expect(LanguageSwitch::make()->getCookieName())
        ->toBe('configured_locale');
});

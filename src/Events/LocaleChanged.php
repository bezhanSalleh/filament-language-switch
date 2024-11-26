<?php

namespace BezhanSalleh\FilamentLanguageSwitch\Events;

class LocaleChanged
{
    public function __construct(
        public string $locale
    ) {}
}

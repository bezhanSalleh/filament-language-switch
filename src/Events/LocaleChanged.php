<?php

namespace BezhanSalleh\LanguageSwitch\Events;

class LocaleChanged
{
    public function __construct(
        public string $locale
    ) {}
}

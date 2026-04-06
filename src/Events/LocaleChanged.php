<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Events;

class LocaleChanged
{
    public function __construct(
        public string $locale
    ) {}
}

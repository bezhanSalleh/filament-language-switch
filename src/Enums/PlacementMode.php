<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Enums;

enum PlacementMode: string
{
    case Pinned = 'pinned';

    case Static = 'static';

    case Relative = 'relative';
}

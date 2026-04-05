<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Enums;

enum PlacementMode: string
{
    case Fixed = 'fixed';

    case Sticky = 'sticky';

    case Relative = 'relative';
}

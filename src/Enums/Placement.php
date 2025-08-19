<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Enums;

enum Placement: string
{
    case TopLeft = 'top-left';

    case TopCenter = 'top-center';

    case TopRight = 'top-right';

    case BottomLeft = 'bottom-left';

    case BottomCenter = 'bottom-center';

    case BottomRight = 'bottom-right';
}

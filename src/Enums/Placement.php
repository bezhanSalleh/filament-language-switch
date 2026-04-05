<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Enums;

use Filament\View\PanelsRenderHook;

enum Placement: string
{
    case TopStart = 'top-start';

    case TopCenter = 'top-center';

    case TopEnd = 'top-end';

    case BottomStart = 'bottom-start';

    case BottomCenter = 'bottom-center';

    case BottomEnd = 'bottom-end';

    public function isTop(): bool
    {
        return in_array($this, [self::TopStart, self::TopCenter, self::TopEnd], true);
    }

    public function anchorHook(): string
    {
        return match ($this) {
            self::TopStart, self::TopCenter, self::TopEnd => PanelsRenderHook::BODY_START,
            self::BottomStart, self::BottomCenter, self::BottomEnd => PanelsRenderHook::BODY_END,
        };
    }
}

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

    public function toPositionClasses(): string
    {
        return match ($this) {
            self::TopStart => 'top-0 inset-x-0 justify-start',
            self::TopCenter => 'top-0 inset-x-0 justify-center',
            self::TopEnd => 'top-0 inset-x-0 justify-end',
            self::BottomStart => 'bottom-0 inset-x-0 justify-start',
            self::BottomCenter => 'bottom-0 inset-x-0 justify-center',
            self::BottomEnd => 'bottom-0 inset-x-0 justify-end',
        };
    }

    public function toSelfAlignClass(): string
    {
        return match ($this) {
            self::TopStart, self::BottomStart => 'self-start',
            self::TopCenter, self::BottomCenter => 'self-center',
            self::TopEnd, self::BottomEnd => 'self-end',
        };
    }

    public function isTop(): bool
    {
        return in_array($this, [self::TopStart, self::TopCenter, self::TopEnd], true);
    }

    public function anchorHook(): string
    {
        return match ($this) {
            self::TopStart, self::TopCenter, self::TopEnd => PanelsRenderHook::SIMPLE_LAYOUT_START,
            self::BottomStart, self::BottomCenter, self::BottomEnd => PanelsRenderHook::SIMPLE_LAYOUT_END,
        };
    }
}

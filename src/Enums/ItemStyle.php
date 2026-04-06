<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Enums;

enum ItemStyle: string
{
    case FlagWithLabel = 'flag-with-label';

    case FlagOnly = 'flag-only';

    case AvatarWithLabel = 'avatar-with-label';

    case AvatarOnly = 'avatar-only';

    case LabelOnly = 'label-only';

    public function hasLabel(): bool
    {
        return in_array($this, [
            self::FlagWithLabel,
            self::AvatarWithLabel,
            self::LabelOnly,
        ], true);
    }

    public function hasVisual(): bool
    {
        return $this !== self::LabelOnly;
    }

    public function isCompact(): bool
    {
        return in_array($this, [self::FlagOnly, self::AvatarOnly], true);
    }
}

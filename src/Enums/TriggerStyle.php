<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Enums;

enum TriggerStyle: string
{
    case Icon = 'icon';

    case IconLabel = 'icon-label';

    case Avatar = 'avatar';

    case AvatarLabel = 'avatar-label';

    case Flag = 'flag';

    case FlagLabel = 'flag-label';

    case Label = 'label';

    public function hasLabel(): bool
    {
        return in_array($this, [
            self::IconLabel,
            self::AvatarLabel,
            self::FlagLabel,
            self::Label,
        ], true);
    }

    public function hasVisual(): bool
    {
        return $this !== self::Label;
    }

    public function visual(): string
    {
        return match ($this) {
            self::Icon, self::IconLabel => 'icon',
            self::Avatar, self::AvatarLabel => 'avatar',
            self::Flag, self::FlagLabel => 'flag',
            self::Label => 'label',
        };
    }
}

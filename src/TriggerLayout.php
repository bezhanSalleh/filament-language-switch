<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch;

use BezhanSalleh\LanguageSwitch\Enums\TriggerStyle;
use Filament\Support\Icons\Heroicon;

final readonly class TriggerLayout
{
    public function __construct(
        public string $renderContext,
        public bool $isPhysicallyInSidebar,
        public bool $hasTopbar,
        public bool $shouldTeleport,
        public string $placement,
        public string $spacingKey,
        public string $triggerShell,
        public ?string $sidebarVariant,
        public TriggerStyle $triggerStyle,
        public bool $hasLabel,
        public bool $hasVisual,
        public string $contentType,
        public bool $isCircular,
        public bool $shouldHideWhenCollapsed,
        public bool $isUrlFlag,
        public ?string $flagSrc,
        public string $currentLocale,
        public string $currentLabel,
        public string $currentAvatar,
        public string | Heroicon $triggerIcon,
    ) {}
}

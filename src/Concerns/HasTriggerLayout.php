<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Concerns;

use BezhanSalleh\LanguageSwitch\TriggerLayout;
use Filament\View\PanelsRenderHook;

trait HasTriggerLayout
{
    public function getTriggerLayout(): TriggerLayout
    {
        $hook = $this->getResolvedRenderHook();
        $isSimpleLayoutContext = $this->isVisibleOutsidePanels();
        $hasTopbar = $isSimpleLayoutContext
            ? true
            : $this->getCurrentPanel()->hasTopbar();
        $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();

        $classification = $this->classifyHook($hook, $hasTopbar);

        $style = $this->getTriggerStyle();
        $contentType = $style->visual();

        $currentLocale = app()->getLocale();
        $currentFlag = filled($this->getFlags()) ? $this->getFlag($currentLocale) : null;
        $flagSrc = ($contentType === 'flag' && $currentFlag) ? $currentFlag : null;

        $isOutsidePanel = $classification['context'] === 'outside-panel';
        $outsidePanelPlacement = $isOutsidePanel ? $this->getOutsidePanelPlacement() : null;
        $outsidePanelPlacementMode = $isOutsidePanel ? $this->getOutsidePanelPlacementMode() : null;

        return new TriggerLayout(
            renderContext: $classification['context'],
            isPhysicallyInSidebar: $classification['inSidebar'],
            hasTopbar: $hasTopbar,
            shouldTeleport: ! $classification['inSidebar'],
            placement: $this->getDropdownPlacement() ?? $classification['placement'],
            spacingKey: $classification['spacingKey'],
            triggerShell: $classification['context'] === 'user-menu' ? 'dropdown-list-item' : 'button',
            sidebarVariant: $classification['sidebarVariant'],
            triggerStyle: $style,
            hasLabel: $style->hasLabel(),
            hasVisual: $style->hasVisual(),
            contentType: $contentType,
            isCircular: $this->isCircular(),
            shouldHideWhenCollapsed: $classification['hideWhenCollapsed'] && $isSidebarCollapsibleOnDesktop,
            isUrlFlag: $flagSrc !== null && filter_var($flagSrc, FILTER_VALIDATE_URL) !== false,
            flagSrc: $flagSrc,
            currentLocale: $currentLocale,
            currentLabel: $this->getLabel($currentLocale),
            currentAvatar: $this->getAvatar($currentLocale),
            triggerIcon: $this->getTriggerIcon(),
            outsidePanelPlacement: $outsidePanelPlacement,
            outsidePanelPlacementMode: $outsidePanelPlacementMode,
            outsidePanelPositionClasses: $outsidePanelPlacement?->toPositionClasses(),
            outsidePanelSelfAlignClass: $outsidePanelPlacement?->toSelfAlignClass(),
        );
    }

    /**
     * @return array<string, bool|string|null>
     */
    protected function classifyHook(string $hook, bool $hasTopbar): array
    {
        $sidebarLogoHooks = [
            PanelsRenderHook::SIDEBAR_LOGO_BEFORE,
            PanelsRenderHook::SIDEBAR_LOGO_AFTER,
        ];

        $sidebarBodyHooks = [
            PanelsRenderHook::SIDEBAR_START,
            PanelsRenderHook::SIDEBAR_NAV_START,
            PanelsRenderHook::SIDEBAR_NAV_END,
            PanelsRenderHook::SIDEBAR_FOOTER,
        ];

        $userMenuOuterHooks = [
            PanelsRenderHook::USER_MENU_BEFORE,
            PanelsRenderHook::USER_MENU_AFTER,
        ];

        $userMenuProfileHooks = [
            PanelsRenderHook::USER_MENU_PROFILE_BEFORE,
            PanelsRenderHook::USER_MENU_PROFILE_AFTER,
        ];

        $outsidePanelHooks = [
            PanelsRenderHook::SIMPLE_LAYOUT_START,
            PanelsRenderHook::SIMPLE_LAYOUT_END,
        ];

        $inSidebar = in_array($hook, $sidebarLogoHooks, true)
            || in_array($hook, $sidebarBodyHooks, true)
            || (in_array($hook, $userMenuOuterHooks, true) && ! $hasTopbar)
            || (in_array($hook, $userMenuProfileHooks, true) && ! $hasTopbar);

        $context = match (true) {
            in_array($hook, $outsidePanelHooks, true) => 'outside-panel',
            in_array($hook, $sidebarLogoHooks, true) => 'topbar',
            in_array($hook, $sidebarBodyHooks, true) => 'sidebar',
            in_array($hook, $userMenuProfileHooks, true) => 'user-menu',
            in_array($hook, $userMenuOuterHooks, true) => $hasTopbar ? 'topbar' : 'sidebar',
            default => 'topbar',
        };

        $placement = match ($context) {
            'sidebar' => 'top-end',
            'outside-panel' => $hook === PanelsRenderHook::SIMPLE_LAYOUT_END ? 'top-end' : 'bottom-end',
            default => 'bottom-end',
        };

        $spacingKey = match ($hook) {
            PanelsRenderHook::SIDEBAR_NAV_START, PanelsRenderHook::SIDEBAR_NAV_END => 'sidebar-nav',
            PanelsRenderHook::TOPBAR_START, PanelsRenderHook::TOPBAR_END => 'topbar-edge',
            PanelsRenderHook::USER_MENU_BEFORE => $hasTopbar ? 'user-menu-before' : 'none',
            PanelsRenderHook::USER_MENU_AFTER => $hasTopbar ? 'user-menu-after' : 'none',
            default => 'none',
        };

        $sidebarVariant = match ($context) {
            'sidebar' => match (true) {
                in_array($hook, $userMenuOuterHooks, true) => 'footer-item',
                default => 'nav-item',
            },
            default => null,
        };

        $hideWhenCollapsed = in_array($hook, $sidebarLogoHooks, true);

        return [
            'inSidebar' => $inSidebar,
            'context' => $context,
            'placement' => $placement,
            'spacingKey' => $spacingKey,
            'sidebarVariant' => $sidebarVariant,
            'hideWhenCollapsed' => $hideWhenCollapsed,
        ];
    }
}

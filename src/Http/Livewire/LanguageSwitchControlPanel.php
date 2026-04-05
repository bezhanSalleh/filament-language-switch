<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Http\Livewire;

use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class LanguageSwitchControlPanel extends Component
{
    protected const SESSION_KEY = 'language-switch-control';

    public bool $live = true;

    public bool $isDirty = false;

    public bool $topbar = true;

    public string $displayMode = 'dropdown';

    public bool $circular = false;

    public int $columns = 1;

    public bool $nativeLabel = false;

    public bool $flagsOnly = false;

    public bool $useFlags = false;

    public string $modalWidth = 'sm';

    public bool $modalSlideOver = false;

    public string $renderHook = '';

    public string $triggerStyle = '';

    public string $triggerIcon = '';

    public bool $outsidePanels = false;

    public string $outsidePanelPlacement = 'top-end';

    public string $outsidePanelPlacementMode = 'static';

    public string $outsidePanelsRenderHook = '';

    public function mount(): void
    {
        $panel = filament()->getCurrentOrDefaultPanel();

        $this->topbar = $panel->hasTopbar();
        $this->live = LanguageSwitch::make()->isControlPanelLive();

        $overrides = session(self::SESSION_KEY, []);

        foreach ($overrides as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }

        $validOutsidePanelsRenderHooks = [
            '',
            'panels::user-menu.before',
            'panels::user-menu.after',
            'panels::user-menu.profile.before',
            'panels::user-menu.profile.after',
        ];

        if (! in_array($this->outsidePanelsRenderHook, $validOutsidePanelsRenderHooks, true)) {
            $this->outsidePanelsRenderHook = '';
            session()->put(self::SESSION_KEY, array_merge($overrides, [
                'outsidePanelsRenderHook' => '',
            ]));
        }

        $validPlacementModes = ['pinned', 'static', 'relative'];

        if (! in_array($this->outsidePanelPlacementMode, $validPlacementModes, true)) {
            $this->outsidePanelPlacementMode = 'static';
            session()->put(self::SESSION_KEY, array_merge(
                session(self::SESSION_KEY, []),
                ['outsidePanelPlacementMode' => 'static'],
            ));
        }
    }

    public function updated($property, $value = null): void
    {
        if (in_array($property, ['live', 'isDirty'], true)) {
            return;
        }

        if ($property === 'topbar') {
            filament()->getCurrentOrDefaultPanel()->topbar($this->topbar);
        }

        if ($property === 'useFlags' && ! $this->useFlags) {
            $this->flagsOnly = false;

            if (in_array($this->triggerStyle, ['flag', 'flag-label'], true)) {
                $this->triggerStyle = '';
            }
        }

        session()->put(self::SESSION_KEY, [
            'topbar' => $this->topbar,
            'displayMode' => $this->displayMode,
            'circular' => $this->circular,
            'columns' => $this->columns,
            'nativeLabel' => $this->nativeLabel,
            'flagsOnly' => $this->flagsOnly,
            'useFlags' => $this->useFlags,
            'modalWidth' => $this->modalWidth,
            'modalSlideOver' => $this->modalSlideOver,
            'renderHook' => $this->renderHook,
            'triggerStyle' => $this->triggerStyle,
            'triggerIcon' => $this->triggerIcon,
            'outsidePanels' => $this->outsidePanels,
            'outsidePanelPlacement' => $this->outsidePanelPlacement,
            'outsidePanelPlacementMode' => $this->outsidePanelPlacementMode,
            'outsidePanelsRenderHook' => $this->outsidePanelsRenderHook,
        ]);

        if ($this->live) {
            $this->redirect(request()->header('Referer', url()->current()));

            return;
        }

        $this->isDirty = true;
    }

    public function applyIcon(): void
    {
        $this->updated('triggerIcon');
    }

    public function applyOverrides(): void
    {
        $this->isDirty = false;

        $this->redirect(request()->header('Referer', url()->current()));
    }

    public function resetOverrides(): void
    {
        session()->forget(self::SESSION_KEY);

        $this->redirect(request()->header('Referer', url()->current()));
    }

    public function render(): View
    {
        return view('language-switch::control-panel', [
            'hasTopbar' => $this->topbar,
        ]);
    }
}

<?php

declare(strict_types=1);

namespace BezhanSalleh\LanguageSwitch\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class LanguageSwitchDebugPanel extends Component
{
    public bool $isOpen = false;

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

    public function mount(): void
    {
        $this->topbar = filament()->getCurrentOrDefaultPanel()->hasTopbar();

        $overrides = session('language-switch-debug', []);

        foreach ($overrides as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    public function updated(string $property): void
    {
        if ($property === 'isOpen') {
            return;
        }

        if ($property === 'topbar') {
            filament()->getCurrentOrDefaultPanel()->topbar($this->topbar);
        }

        if ($property === 'useFlags' && ! $this->useFlags) {
            $this->flagsOnly = false;

            if (in_array($this->triggerStyle, ['flag', 'flag-label'])) {
                $this->triggerStyle = '';
            }
        }

        session()->put('language-switch-debug', [
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
        ]);

        $this->redirect(request()->header('Referer', url()->current()));
    }

    public function resetDebug(): void
    {
        session()->forget('language-switch-debug');

        $this->redirect(request()->header('Referer', url()->current()));
    }

    public function render(): View
    {
        return view('language-switch::debug-panel', [
            'hasTopbar' => $this->topbar,
        ]);
    }
}

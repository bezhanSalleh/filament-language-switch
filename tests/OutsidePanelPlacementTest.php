<?php

use BezhanSalleh\LanguageSwitch\Enums\Placement;
use BezhanSalleh\LanguageSwitch\Enums\PlacementMode;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Illuminate\Support\Facades\Route;

function renderPinnedOutsidePanelSwitch(Placement $placement): string
{
    $route = Route::get('/admin/login', fn () => '')->name('filament.admin.auth.login');
    request()->setRouteResolver(fn () => $route);

    LanguageSwitch::configureUsing(fn (LanguageSwitch $switch) => $switch
        ->locales(['en', 'fr'])
        ->visible(outsidePanels: true)
        ->outsidePanelPlacement($placement, PlacementMode::Pinned));

    return view('language-switch::language-switch')->render();
}

function pinnedWrapperClasses(string $html): array
{
    $dom = new DOMDocument;
    @$dom->loadHTML($html);

    $wrapper = (new DOMXPath($dom))
        ->query('//*[contains(concat(" ", normalize-space(@class), " "), " fi-ls-pinned ")]')
        ->item(0);

    expect($wrapper)->not->toBeNull();

    return preg_split('/\s+/', trim($wrapper->getAttribute('class')));
}

it('does not use a transform on the pinned wrapper', function (Placement $placement) {
    // A transformed ancestor becomes the containing block for the fixed-position
    // dropdown panel, pushing it off-screen (#167).
    expect(pinnedWrapperClasses(renderPinnedOutsidePanelSwitch($placement)))
        ->each->not->toMatch('/(^|:)-?translate-/');
})->with(Placement::cases());

it('centres pinned centre placements without a transform', function (Placement $placement, string $edge) {
    expect(pinnedWrapperClasses(renderPinnedOutsidePanelSwitch($placement)))
        ->toContain('fixed', $edge, 'inset-x-0', 'mx-auto', 'w-fit')
        ->not->toContain('left-1/2');
})->with([
    'top center' => [Placement::TopCenter, 'top-0'],
    'bottom center' => [Placement::BottomCenter, 'bottom-0'],
]);

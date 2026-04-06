<a href="https://github.com/bezhansalleh/filament-language-switch" class="filament-hidden">

![Filament Language Switch](https://repository-images.githubusercontent.com/506847060/81f73ae9-6cef-4f89-a4cf-47de0412e0b5 "Filament Language Switch")

</a>
<p align="center">
    <a href="https://filamentphp.com/docs/5.x/introduction/installation">
        <img alt="FILAMENT 5.x" src="https://img.shields.io/badge/FILAMENT-5.x-EBB304?style=for-the-badge">
    </a>
    <a href="https://filamentphp.com/docs/4.x/introduction/installation">
        <img alt="FILAMENT 5.x" src="https://img.shields.io/badge/FILAMENT-4.x-EBB304?style=for-the-badge">
    </a>
    <a href="https://packagist.org/packages/bezhansalleh/filament-language-switch">
        <img alt="Packagist" src="https://img.shields.io/packagist/v/bezhansalleh/filament-language-switch.svg?style=for-the-badge&logo=packagist">
    </a>
    <a href="https://packagist.org/packages/bezhansalleh/filament-language-switch">
        <img alt="Downloads" src="https://img.shields.io/packagist/dt/bezhansalleh/filament-language-switch.svg?style=for-the-badge">
    </a>
</p>

# Language Switch

Zero-config language switching for Filament Panels. Drop it in, provide your locales, and you're done. The plugin auto-detects your panel layout and renders in the right place with the right design — topbar icon button, sidebar nav item, user menu item — without any manual wiring.

## Table of Contents

- [Installation](#installation)
- [Quick Start](#quick-start)
- [Display Modes](#display-modes)
- [Trigger](#trigger)
- [Flags](#flags)
- [Labels](#labels)
- [Appearance](#appearance)
- [Placement](#placement)
- [Outside Panels](#outside-panels)
- [Inline Embedding](#inline-embedding-in-custom-pages)
- [Panel Exclusion](#panel-exclusion)
- [User Preferred Locale](#user-preferred-locale)
- [Customization](#customization)
- [Event](#event)
- [Control Panel](#control-panel)
- [Full Example](#full-example)
- [Upgrading](#upgrading)
- [Version Support](#version-support)

## Installation

```bash
composer require bezhansalleh/filament-language-switch
```

> [!IMPORTANT]
> The plugin follows Filament's theming rules. So, to use the plugin create a custom theme if you haven't already, and add the following line to your `theme.css` file:

```php
@source '../../../../vendor/bezhansalleh/filament-language-switch/**';
```
Now build your theme using: 
```bash
npm run build
```

Build your theme:

```bash
npm run build
```

## Quick Start

Add this to any service provider's `boot()` method:

```php
use BezhanSalleh\LanguageSwitch\LanguageSwitch;

public function boot(): void
{
    LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch->locales(['en', 'fr', 'ar']);
    });
}
```

That's it. The switch appears in your topbar automatically. When the panel has no topbar, it moves to the sidebar.

## Display Modes

### Dropdown (default)

The trigger opens a dropdown with your locales. The list is scrollable by default (`max-height: 18rem`), so it stays usable with many locales:

```php
$switch->locales(['en', 'fr', 'ar']);
```

### Modal

Opens a modal dialog instead. Better for many languages:

```php
use BezhanSalleh\LanguageSwitch\Enums\DisplayMode;

$switch
    ->locales(['en', 'fr', 'ar', 'de', 'es', 'pt', 'ja', 'ko', 'zh'])
    ->displayMode(DisplayMode::Modal)
    ->modalWidth('lg');
```

### Modal with Grid

Arrange locales in columns:

```php
$switch
    ->displayMode(DisplayMode::Modal)
    ->columns(2)
    ->modalWidth('lg');
```

### Slide-over

```php
$switch
    ->displayMode(DisplayMode::Modal)
    ->modalSlideOver();
```

### Modal Heading & Icon

```php
$switch
    ->displayMode(DisplayMode::Modal)
    ->modalHeading('Choose Language')
    ->modalIcon('heroicon-o-language')
    ->modalIconColor('primary');
```

## Trigger

The trigger is configured through a single unified `trigger()` method that takes a style and/or an icon. Both are optional — pass whichever you want to override.

### Trigger Style

Use the `TriggerStyle` enum to control what the trigger shows. The default adapts to the render context automatically (e.g. flag if you've set flags, otherwise icon in topbar; icon+label in sidebar/user menu), so you only need to call this when you want to override it:

```php
use BezhanSalleh\LanguageSwitch\Enums\TriggerStyle;

// Visual only
$switch->trigger(style: TriggerStyle::Icon);    // language icon
$switch->trigger(style: TriggerStyle::Flag);    // flag image (requires ->flags())
$switch->trigger(style: TriggerStyle::Avatar);  // locale abbreviation (EN, FR, AR)

// Visual with label
$switch->trigger(style: TriggerStyle::IconLabel);    // icon + locale name
$switch->trigger(style: TriggerStyle::FlagLabel);    // flag + locale name
$switch->trigger(style: TriggerStyle::AvatarLabel);  // abbreviation + locale name

// Label only — no visual
$switch->trigger(style: TriggerStyle::Label);
```

### Trigger Icon

The trigger uses the language icon by default. Pass any icon from your installed [Blade Icons](https://blade-ui-kit.com/blade-icons) packages, or a `Heroicon` enum case:

```php
use Filament\Support\Icons\Heroicon;

// Just change the icon (style stays as default)
$switch->trigger(icon: Heroicon::GlobeAlt);

// Any Blade Icons string works too
$switch->trigger(icon: 'heroicon-o-globe-alt');
$switch->trigger(icon: 'phosphor-translate');

// Or change both in one call
$switch->trigger(
    style: TriggerStyle::IconLabel,
    icon: Heroicon::GlobeAlt,
);
```

You can also override the icon globally via Filament's icon alias system:

```php
use Filament\Support\Facades\FilamentIcon;

FilamentIcon::register([
    'language-switch::trigger' => 'heroicon-o-globe-alt',
]);
```

## Flags

Associate each locale with a flag image:

```php
$switch->flags([
    'en' => asset('flags/us.svg'),
    'fr' => asset('flags/fr.svg'),
    'ar' => asset('flags/sa.svg'),
]);
```

### Item Style

Control what each locale item shows in the dropdown or modal. The default auto-detects: `FlagWithLabel` when flags are configured, `AvatarWithLabel` otherwise.

```php
use BezhanSalleh\LanguageSwitch\Enums\ItemStyle;

$switch->itemStyle(ItemStyle::FlagOnly);        // flag images only, tooltips on hover
$switch->itemStyle(ItemStyle::AvatarOnly);      // abbreviations only (EN, FR), tooltips on hover
$switch->itemStyle(ItemStyle::LabelOnly);       // text labels only, no visual
$switch->itemStyle(ItemStyle::FlagWithLabel);    // flag + locale name (default with flags)
$switch->itemStyle(ItemStyle::AvatarWithLabel);  // abbreviation + locale name (default without flags)
```

Compact styles (`FlagOnly`, `AvatarOnly`) use the same tight cell layout with tooltips. In modal mode, `FlagOnly` renders as a showcase grid with radio-card selection; `AvatarOnly` falls back to label-only cards since the abbreviation is too small for a showcase.

```php
$switch
    ->flags([...])
    ->itemStyle(ItemStyle::FlagOnly)
    ->displayMode(DisplayMode::Modal)
    ->columns(3)
    ->modalWidth('lg');
```

## Labels

### Custom Labels

Override the auto-generated locale names:

```php
$switch->labels([
    'pt_BR' => 'Brasileiro',
    'pt_PT' => 'Europeu',
]);
```

### Native Labels

Show each locale name in its own language (e.g., "Fran&ccedil;ais" instead of "French"):

```php
$switch->nativeLabel();
```

### Display Locale

Change the language used for auto-generated labels:

```php
// Labels generated in French (e.g., "Anglais" for English)
$switch->displayLocale('fr');
```

## Appearance

### Circular

Make flags and avatars fully rounded:

```php
$switch->circular();
```

### Flag & Avatar Sizing (Modal)

Control sizes in modal radio cards:

```php
$switch
    ->displayMode(DisplayMode::Modal)
    ->flagHeight('h-20')     // Default: 'h-16'
    ->avatarHeight('size-10'); // Default: 'size-8'
```

## Placement

### Render Hook

Place the switch at any [Filament render hook](https://filamentphp.com/docs/5.x/support/render-hooks):

```php
use Filament\View\PanelsRenderHook;

$switch->renderHook(PanelsRenderHook::SIDEBAR_NAV_END);
```

The trigger automatically adapts its design to match the surrounding UI:

| Hook Location | Trigger Design |
|---|---|
| `GLOBAL_SEARCH_*`, `TOPBAR_*` | Icon button (matches topbar icons) |
| `SIDEBAR_LOGO_*` | Icon button (compact, hides when sidebar collapses) |
| `SIDEBAR_START`, `SIDEBAR_NAV_*`, `SIDEBAR_FOOTER` | Sidebar nav item (matches Dashboard, Welcome, etc.) |
| `USER_MENU_BEFORE/AFTER` (topbar on) | Icon button inside user menu area |
| `USER_MENU_BEFORE/AFTER` (topbar off) | Sidebar footer button (matches notifications) |
| `USER_MENU_PROFILE_*` | Dropdown list item (matches user menu items) |
| `SIMPLE_LAYOUT_START/END` | Floating button (see [Outside Panels](#outside-panels)) |

> **Any other hook works too.** If you pass a hook the plugin doesn't explicitly classify, it still registers and renders a default icon button — but the visual fit is on you. Use the stable CSS hooks (`fi-ls`, `fi-ls-trigger`, etc.) from your own stylesheet, or publish the views for structural changes.

### Smart Defaults

When you don't set a render hook, the plugin picks the best one based on your panel:

| Panel Config | Default Hook | Where it appears |
|---|---|---|
| Topbar enabled | `GLOBAL_SEARCH_AFTER` | Topbar, after search bar |
| Topbar disabled | `SIDEBAR_LOGO_AFTER` | Sidebar header, next to the logo |

To render inside the user menu as a menu item, set the render hook explicitly:

```php
$switch->renderHook(PanelsRenderHook::USER_MENU_PROFILE_AFTER);
```

### Dropdown Placement

Control which direction the dropdown opens:

```php
$switch->dropdownPlacement('top-end');
```

### Max Height

The dropdown is scrollable by default (`18rem`). Override it for a different limit, or pass `'max-content'` to disable scrolling:

```php
$switch->maxHeight('30rem');
$switch->maxHeight('max-content'); // no scroll, grows to fit content
```

## Outside Panels

Filament's unauthenticated pages (login, register, password reset, email verification) render in a **simple layout** — no sidebar, no topbar, just the centered form card. You can show the language switcher on these pages so visitors can translate the UI before they sign in.

```php
use BezhanSalleh\LanguageSwitch\Enums\Placement;

$switch
    ->visible(outsidePanels: true)
    ->outsidePanelPlacement(Placement::TopEnd);
```

`visible()` takes two independent toggles — `insidePanels` (default `true`) and `outsidePanels` (default `false`) — so you can enable either context on its own, both, or neither. For example, to render **only** on the simple-layout pages and hide the switcher once the user is inside the panel:

```php
$switch->visible(insidePanels: false, outsidePanels: true);
```

By default the switcher renders as a content-sized pill anchored to the chosen `Placement`. All six placements are RTL-aware — `start`/`end` auto-flip for right-to-left locales:

| Placement | Position |
|---|---|
| `Placement::TopStart` | Top-left (LTR) / top-right (RTL) |
| `Placement::TopCenter` | Top-center |
| `Placement::TopEnd` | Top-right (LTR) / top-left (RTL) |
| `Placement::BottomStart` | Bottom-left (LTR) / bottom-right (RTL) |
| `Placement::BottomCenter` | Bottom-center |
| `Placement::BottomEnd` | Bottom-right (LTR) / bottom-left (RTL) |

### Placement mode

`outsidePanelPlacement()` accepts an optional second argument that controls **how** the switcher is attached to the page. Three modes, each with predictable CSS semantics — the names are chosen so they match what you'd read in the published view:

```php
use BezhanSalleh\LanguageSwitch\Enums\Placement;
use BezhanSalleh\LanguageSwitch\Enums\PlacementMode;

$switch->outsidePanelPlacement(Placement::TopCenter, PlacementMode::Pinned);
```

| Mode | CSS | Behavior |
|---|---|---|
| **`PlacementMode::Static`** *(default)* | `position: static` | Renders **in the document flow** inside `.fi-simple-layout`, above the form card for `Top*` placements or below it for `Bottom*`. Scrolls with the page. Content-sized pill aligned horizontally via `w-fit` + `mx-auto` / `ms-auto`. |
| **`PlacementMode::Pinned`** | `position: fixed` | **Stays visible while scrolling** — pinned to the viewport at the chosen corner/edge as a content-sized pill. Use this when the switcher should always be reachable (e.g. long registration forms). |
| **`PlacementMode::Relative`** | `position: relative` | Same visual as `Static` out of the box, but the element is **positioned**, so you can offset it via custom CSS (`top: 1rem`, `inset-inline-start: 2rem`, etc.) in your theme file without publishing the view. |

> **Naming note:** we use `Pinned` instead of `Fixed` on purpose. In CSS, `position: fixed` means "always visible, stays on screen during scroll" — which in the dev brain is usually called "sticky". To avoid that collision every time someone opens the blade file, the mode is named after the *intent* (`Pinned` = pinned to viewport), while `Static` and `Relative` use the literal CSS names because those map 1:1 to their CSS behavior.

### Which routes it shows on

By default the switcher appears on Filament's standard auth routes. Override the list if your panel uses different route names:

```php
$switch->outsidePanelRoutes([
    'auth.login',
    'auth.register',
    'auth.password-reset.request',
    'auth.password-reset.reset',
]);
```

The default list is `['auth.login', 'auth.profile', 'auth.register']`. The profile route is automatically excluded from the match unless the current panel uses a simple profile page (`->profile(isSimple: true)`), since a full-layout profile page already has a topbar/sidebar where the switcher lives.

### Render hook anchor (auto-derived)

The anchor hook is derived from **both** the placement and the mode — you don't pick it manually:

| Mode | Top placement | Bottom placement | Why |
|---|---|---|---|
| `Pinned` | `BODY_START` | `BODY_END` | Pinned uses `position: fixed`, so the element needs a parent that gives reliable viewport-relative positioning. Direct body child is ideal — no flex-parent or transform containing block in the ancestor chain. |
| `Static` / `Relative` | `SIMPLE_LAYOUT_START` | `SIMPLE_LAYOUT_END` | In-flow elements are anchored **inside `.fi-simple-layout`** (which is `min-h-dvh flex-col`). This way the element shares the viewport with the centered form card instead of extending the body height and adding an unwanted page scroll. |

You can still override explicitly — pass any hook name to `outsidePanelsRenderHook()`:

```php
use Filament\View\PanelsRenderHook;

// Dock as a profile item inside the user menu dropdown
$switch->outsidePanelsRenderHook(PanelsRenderHook::USER_MENU_PROFILE_AFTER);

// Or force body-level anchoring for an in-flow mode
$switch
    ->outsidePanelPlacement(Placement::BottomCenter, PlacementMode::Static)
    ->outsidePanelsRenderHook(PanelsRenderHook::BODY_END);
```

### Auto-docking into the user menu

When **all** of these are true, `TopEnd` **automatically docks into the user menu** via `USER_MENU_BEFORE` instead of anchoring as a pinned overlay:

- `PlacementMode::Pinned`
- The current route is in the outside-panel list (e.g. a simple profile page)
- The user is authenticated
- The panel has a user menu

This avoids the collision between the pinned pill and Filament's own `.fi-simple-layout-header` (also anchored at `top-0 end-0`). `Static` and `Relative` modes don't trigger auto-docking because they're in flow and can't collide with the header.

To force a pinned overlay regardless, pin a different hook explicitly:

```php
use Filament\View\PanelsRenderHook;

// Force a body-start overlay even when a user menu is present
$switch
    ->outsidePanelPlacement(Placement::TopEnd, PlacementMode::Pinned)
    ->outsidePanelsRenderHook(PanelsRenderHook::BODY_START);
```

## Inline embedding in custom pages

The render-hook system handles the common cases automatically — topbar, sidebar, user menu, simple layout. If you need to drop the switcher into a custom page section or arbitrary location in your own views, use the Blade component:

```blade
<x-language-switch::inline />
```

It mounts the same `<livewire:language-switch-component>` directly at the tag's position — no render hook required. The trigger uses whatever styling your current configuration resolves to (for example, if your effective render hook is in the topbar, you'll get a topbar-style icon button inline in your view). If you need a different visual in your custom location, override with `->renderHook(...)` in a dedicated configureUsing callback or publish the views for full control.

Passing an explicit key is recommended when embedding in a parent component that re-renders, or when placing multiple instances on the same page:

```blade
<x-language-switch::inline key="switch-header" />
...
<x-language-switch::inline key="switch-footer" />
```

If omitted, a unique key is auto-generated via `uniqid()` on every render, which is fine for static pages but can cause the component to re-mount unnecessarily on dynamic parent updates.

## Panel Exclusion

Hide the switch from specific panels:

```php
$switch->excludes(['admin']);
```

## User Preferred Locale

Resolve the user's preferred locale from their profile or any source:

```php
$switch->userPreferredLocale(fn () => auth()->user()?->locale);
```

The locale resolution order is:
1. Session
2. Query parameter (`?locale=`)
3. User preferred locale (this setting)
4. Browser `Accept-Language` header
5. Cookie
6. `app.locale` config

## Customization

For deep customization, publish the plugin's views and edit them in your app:

```bash
php artisan vendor:publish --tag="language-switch-views"
```

Published views live in `resources/views/vendor/language-switch/` and override the package versions automatically. For small tweaks, prefer targeting the plugin's stable CSS hooks (`fi-ls`, `fi-ls-trigger`, `fi-ls-item`, etc.) from your own stylesheet rather than publishing views.

## Event

A `LocaleChanged` event fires whenever the locale switches:

```php
use BezhanSalleh\LanguageSwitch\Events\LocaleChanged;
use Illuminate\Support\Facades\Event;

Event::listen(function (LocaleChanged $event) {
    auth()->user()?->update(['locale' => $event->locale]);
});
```

## Control Panel

A floating developer configurator that lets you hot-swap every configuration option live in the browser — handy for previewing trigger styles, testing render-hook placements, and checking the outside-panel modes without editing your service provider.

### Enabling it

Opt in explicitly via `controlPanel()`:

```php
LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
    $switch
        ->locales(['en', 'fr', 'ar'])
        ->controlPanel();
});
```

Disabled by default. When you call `controlPanel()` (or `controlPanel(true)`) **and** the app is running with `APP_ENV=local` + `APP_DEBUG=true`, a small language-icon button appears at the bottom-end of every panel page. The local + debug requirement is a safety guardrail — even if you leave `controlPanel(true)` committed, it won't render in production.

To disable it explicitly (e.g. in a shared configuration):

```php
$switch->controlPanel(false);
```

### Live vs manual mode

`controlPanel()` takes two arguments: `controlPanel(bool $enabled = true, bool $live = true)`. By default every change (toggle, select) is applied immediately via a Livewire round-trip. If you'd rather stage multiple changes and commit them together, pass `live: false`:

```php
$switch->controlPanel(live: false);          // enabled + staged (Apply button)
$switch->controlPanel(true, live: false);    // same thing, explicit
```

In manual mode, changes are accumulated in the session and the panel footer shows an **Apply** button (only enabled when you have pending changes). Click it to commit everything in one reload. **Reset** still works the same way — it wipes every accumulated override and restores your configured defaults.

### Sections

The panel is organized as an accordion — click a section to expand it; opening one collapses the others, so it stays compact on both mobile and desktop:

- **Trigger** — topbar on/off, trigger style, trigger icon, render hook
- **Display** — dropdown / modal mode, modal width, columns, slide-over
- **Appearance** — circular, native labels, use flags, item style
- **Outside Panels** — enable toggle, placement, placement mode (`Pinned` / `Static` / `Relative`), and an explicit render hook override (defaults to auto, with user-menu docking targets as alternatives)

## Full Example

```php
use BezhanSalleh\LanguageSwitch\Enums\DisplayMode;
use BezhanSalleh\LanguageSwitch\Enums\TriggerStyle;
use BezhanSalleh\LanguageSwitch\LanguageSwitch;
use Filament\Support\Icons\Heroicon;

LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
    $switch
        ->locales(['en', 'fr', 'ar', 'de', 'es'])
        ->flags([
            'en' => asset('flags/us.svg'),
            'fr' => asset('flags/fr.svg'),
            'ar' => asset('flags/sa.svg'),
            'de' => asset('flags/de.svg'),
            'es' => asset('flags/es.svg'),
        ])
        ->displayMode(DisplayMode::Modal)
        ->columns(2)
        ->modalWidth('lg')
        ->circular()
        ->nativeLabel()
        ->trigger(
            style: TriggerStyle::FlagLabel,
            icon: Heroicon::GlobeAlt,
        )
        ->excludes(['admin'])
        ->userPreferredLocale(fn () => auth()->user()?->locale);
});
```

## Upgrading from v4 to v5

> **Branches:** v4 lives on [`main`](https://github.com/bezhansalleh/filament-language-switch/tree/main), v5 lives on the `5.x` branch.

If all you do is `->locales([...])` and optionally `->flags([...])`, your v4 configuration already works on v5. The rest of the existing v4 public API — `visible()`, `renderHook()`, `outsidePanelPlacement()`, `excludes()`, etc. — is preserved with the same signatures. Only the items below require action.

### Breaking changes

#### Placement enum rename (RTL-aware)

The `Placement` enum cases were renamed so they auto-flip in right-to-left locales:

| v4 | v5 |
|---|---|
| `Placement::TopLeft` | `Placement::TopStart` |
| `Placement::TopRight` | `Placement::TopEnd` |
| `Placement::BottomLeft` | `Placement::BottomStart` |
| `Placement::BottomRight` | `Placement::BottomEnd` |

`TopCenter` and `BottomCenter` keep their names. Update any `->outsidePanelPlacement(...)` call that references the old cases.

#### `flagsOnly()` replaced by `itemStyle()`

`->flagsOnly()` has been replaced by the `ItemStyle` enum, which covers all visual+label combinations for locale items — not just flags:

| v4 | v5 |
|---|---|
| `->flagsOnly()` | `->itemStyle(ItemStyle::FlagOnly)` |

See [Item Style](#item-style) for the full enum.

#### Default value changes

| Setting | v4 default | v5 default | Restore v4 behavior |
|---|---|---|---|
| `maxHeight` | `'max-content'` (no scroll) | `'18rem'` (scrollable) | `->maxHeight('max-content')` |
| `renderHook` | `'panels::global-search.after'` (always) | `GLOBAL_SEARCH_AFTER` with topbar, `SIDEBAR_LOGO_AFTER` without | `->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER)` |
| `outsidePanelsRenderHook` | `'panels::body.start'` (always) | Derived from placement + `PlacementMode` (see [Outside Panels](#outside-panels)) | `->outsidePanelsRenderHook(PanelsRenderHook::BODY_START)` |
| `outsidePanelPlacement` | `Placement::TopRight` | `Placement::TopEnd` | Same position — only the name changed |

### New features

- **Trigger customization.** `->trigger(style: TriggerStyle::..., icon: ...)` backed by the `TriggerStyle` enum (`Icon`, `IconLabel`, `Avatar`, `AvatarLabel`, `Flag`, `FlagLabel`, `Label`). See [Trigger](#trigger).
- **Item style.** `->itemStyle(ItemStyle::...)` controls what each locale item shows — `FlagWithLabel`, `FlagOnly`, `AvatarWithLabel`, `AvatarOnly`, `LabelOnly`. Replaces `flagsOnly()` with a unified enum. See [Item Style](#item-style).
- **Modal display mode.** `->displayMode(DisplayMode::Modal)` plus `->modalHeading()`, `->modalWidth()`, `->modalSlideOver()`, `->modalIcon()`, `->modalIconColor()`, `->columns()`, `->flagHeight()`, `->avatarHeight()`. See [Display Modes](#display-modes).
- **`PlacementMode` for outside panels.** `->outsidePanelPlacement()` now accepts an optional second argument — `PlacementMode::Static` (default, in document flow), `PlacementMode::Pinned` (`position: fixed`, always visible during scroll), or `PlacementMode::Relative` (in flow, positioned for custom CSS offsets).
- **Auto-docking into the user menu.** When `Pinned` would collide with `.fi-simple-layout-header` (authed user on a simple profile page), the switcher routes to `USER_MENU_BEFORE` automatically.
- **Smart hook classification.** Every supported render hook is classified into a render context (`topbar`, `sidebar`, `user-menu`, `outside-panel`) that picks a matching trigger design — sidebar nav item at `SIDEBAR_NAV_*`, dropdown list item at `USER_MENU_PROFILE_*`, etc.
- **`->dropdownPlacement()`.** Control which direction the dropdown panel opens from the trigger (`bottom-end`, `top-start`, etc.).
- **`<x-language-switch::inline />` Blade component.** Drop the switcher into any view — a form section, a custom page, a sidebar widget — without going through a render hook.
- **Developer Control Panel.** Opt-in via `->controlPanel()`. A floating configurator that hot-swaps every setting live in the browser without editing your service provider. Supports `live: false` for batched Apply.

## Version Support
Only the latest major plugin line receives active development. Older lines stay installable for legacy projects but get no new features; they only receive security fixes while their Filament target is still supported upstream.

| Plugin | Filament | Status |
|---|---|---|
| **[`5.x`](https://github.com/bezhansalleh/filament-language-switch/tree/main)** | [v5](https://filamentphp.com/docs/5.x/introduction/installation) | **Active** — new features + bug fixes (lives on `main`) |
| [`4.x`](https://github.com/bezhansalleh/filament-language-switch/tree/4.x) | [v4](https://filamentphp.com/docs/4.x/introduction/installation) & [v5](https://filamentphp.com/docs/5.x/introduction/installation) | Security fixes only |
| [`3.x`](https://github.com/bezhansalleh/filament-language-switch/tree/3.x) | [v3](https://filamentphp.com/docs/3.x/panels/installation) | End of life — follows Filament v3 EOL |
| [`1.x`](https://github.com/bezhansalleh/filament-language-switch/tree/1.x) | [v2](https://filamentphp.com/docs/2.x/admin/installation) | End of life |

See [CHANGELOG](CHANGELOG.md) for migration notes between majors.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Bezhan Salleh](https://github.com/bezhanSalleh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

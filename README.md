<a href="https://github.com/bezhansalleh/filament-language-switch" class="filament-hidden">

![Filament Language Switch](https://repository-images.githubusercontent.com/506847060/81f73ae9-6cef-4f89-a4cf-47de0412e0b5 "Filament Language Switch")

</a>
<p align="center">
    <a href="https://filamentphp.com/docs/5.x/panels/installation">
        <img alt="FILAMENT 5.x" src="https://img.shields.io/badge/FILAMENT-5.x-EBB304?style=for-the-badge">
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

## Compatibility

| Package | Filament |
|---------|----------|
| [v3](https://github.com/bezhanSalleh/filament-language-switch/tree/3.x) | [v3](https://filamentphp.com/docs/3.x/panels/installation) |
| v4 | v4 & v5 |
| v5 | v5 |

## Installation

```bash
composer require bezhansalleh/filament-language-switch
```

Add the plugin's views to your custom theme so Tailwind picks up the classes:

```css
/* resources/css/filament/admin/theme.css */
@source '../../../../vendor/bezhansalleh/filament-language-switch/resources/views/**/*.blade.php';
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

The trigger opens a dropdown with your locales. The list is scrollable by default (`max-height: 24rem`), so it stays usable with many locales:

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

### Inline

Renders the trigger as a menu item inside the user menu instead of a standalone button. Combine with `displayMode()` to pick what opens (dropdown or modal) when the item is clicked:

```php
$switch->inline();
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

### Flags Only

Show only the flag images without text labels. Items show tooltips on hover:

```php
$switch
    ->flags([...])
    ->flagsOnly();
```

In modal mode, flags-only renders as a showcase grid with radio-card selection:

```php
$switch
    ->flags([...])
    ->flagsOnly()
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

### Smart Defaults

When you don't set a render hook, the plugin picks the best one based on your panel:

| Panel Config | Default Hook | Where it appears |
|---|---|---|
| Topbar enabled | `GLOBAL_SEARCH_AFTER` | Topbar, after search bar |
| Topbar disabled | `SIDEBAR_FOOTER` | Below the sidebar footer |
| `inline()` | `USER_MENU_PROFILE_AFTER` | Inside user menu dropdown |

### Dropdown Placement

Control which direction the dropdown opens:

```php
$switch->dropdownPlacement('top-end');
```

### Max Height

The dropdown is scrollable by default (`24rem`). Override it for a different limit, or pass `'max-content'` to disable scrolling:

```php
$switch->maxHeight('30rem');
$switch->maxHeight('max-content'); // no scroll, grows to fit content
```

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
php artisan vendor:publish --tag="filament-language-switch-views"
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

## Debug Panel

When `APP_DEBUG=true` and `APP_ENV=local`, a floating debug panel appears in the bottom-right corner. It lets you hot-swap every configuration option live in the browser:

- Toggle topbar on/off to test both layouts
- Switch between display modes, trigger styles, render hooks
- Toggle flags, circular, native labels, inline
- Adjust modal width, columns, slide-over
- Change trigger icon
- Reset returns to your configured defaults

No code changes needed — just click and test.

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

## Upgrading

If you're coming from an earlier release of v5, a few APIs were consolidated:

- `triggerStyle('flag-label')` → `trigger(style: TriggerStyle::FlagLabel)`
- `triggerIcon(Heroicon::GlobeAlt)` → `trigger(icon: Heroicon::GlobeAlt)`
- `charAvatarHeight('size-10')` → `avatarHeight('size-10')`
- `contentView()`, `itemView()`, `triggerView()` were removed — publish the views instead (`php artisan vendor:publish --tag="filament-language-switch-views"`).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Bezhan Salleh](https://github.com/bezhanSalleh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

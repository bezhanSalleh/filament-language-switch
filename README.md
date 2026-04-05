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

Zero-config language switching for Filament Panels. Drop it in, provide your locales, and you're done. The plugin auto-detects your panel layout and renders in the right place with the right design.

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

The trigger opens a dropdown with your locales:

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

### Trigger Icon

The trigger uses the language icon by default. Change it to any icon from your installed [Blade Icons](https://blade-ui-kit.com/blade-icons) packages:

```php
use Filament\Support\Icons\Heroicon;

// Using Filament's Heroicon enum
$switch->triggerIcon(Heroicon::GlobeAlt);

// Using any Blade Icons string
$switch->triggerIcon('heroicon-o-globe-alt');
$switch->triggerIcon('phosphor-translate');
```

You can also override it globally via Filament's icon alias system:

```php
use Filament\Support\Facades\FilamentIcon;

FilamentIcon::register([
    'language-switch::trigger' => 'heroicon-o-globe-alt',
]);
```

### Trigger Style

Control what the trigger shows. The default adapts to context automatically, but you can override it:

```php
// Icon only (default for topbar)
$switch->triggerStyle('icon');

// Icon with locale name
$switch->triggerStyle('icon-label');

// Locale abbreviation (EN, FR, AR)
$switch->triggerStyle('avatar');

// Abbreviation with locale name
$switch->triggerStyle('avatar-label');

// Flag image (requires flags() to be set)
$switch->triggerStyle('flag');

// Flag with locale name
$switch->triggerStyle('flag-label');
```

### Inline

Renders the trigger as a menu item inside the user menu instead of a standalone button:

```php
$switch->inline();
```

### Custom Trigger View

Replace the trigger entirely with your own Blade view:

```php
$switch->triggerView('my-views.language-trigger');
```

Your view receives all configuration variables (`$renderContext`, `$triggerStyle`, `$currentLocale`, `$currentLabel`, `$currentFlag`, `$isCircular`, etc.).

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
    ->flagHeight('h-20')       // Default: 'h-16'
    ->charAvatarHeight('size-10'); // Default: 'size-8'
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
| `SIDEBAR_NAV_*`, `SIDEBAR_FOOTER`, `USER_MENU_BEFORE/AFTER` | Sidebar button (matches notifications/panel-switch) |
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

Limit the dropdown list height:

```php
$switch->maxHeight('200px');
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
3. Request input
4. Cookie
5. User preferred locale (this setting)
6. `app.locale` config
7. Browser `Accept-Language` header

## Custom Views

### Custom Locale List

Replace the entire locale list rendering:

```php
$switch->contentView('my-views.locale-list');
```

### Custom Locale Item

Replace how each individual locale renders in the list:

```php
$switch->itemView('my-views.locale-item');
```

### Publishing Views

Publish all views for deep customization:

```bash
php artisan vendor:publish --tag="filament-language-switch-views"
```

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
        ->triggerIcon(Heroicon::GlobeAlt)
        ->triggerStyle('flag-label')
        ->excludes(['admin'])
        ->userPreferredLocale(fn () => auth()->user()?->locale);
});
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Bezhan Salleh](https://github.com/bezhanSalleh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

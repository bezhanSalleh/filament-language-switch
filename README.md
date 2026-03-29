<a href="https://github.com/bezhansalleh/filament-language-switch" class="filament-hidden">

![Filament Language Switch](https://repository-images.githubusercontent.com/506847060/81f73ae9-6cef-4f89-a4cf-47de0412e0b5 "Filament Language Switch")

</a>
<p align="center">
    <a href="https://filamentphp.com/docs/4.x/panels/installation">
        <img alt="FILAMENT 4.x" src="https://img.shields.io/badge/FILAMENT-4.x-EBB304?style=for-the-badge">
    </a>
    <a href="https://filamentphp.com/docs/5.x/panels/installation">
        <img alt="FILAMENT 5.x" src="https://img.shields.io/badge/FILAMENT-5.x-EBB304?style=for-the-badge">
    </a>
    <a href="https://packagist.org/packages/bezhansalleh/filament-language-switch">
        <img alt="Packagist" src="https://img.shields.io/packagist/v/bezhansalleh/filament-language-switch.svg?style=for-the-badge&logo=packagist">
    </a>
    <a href="https://github.com/bezhansalleh/filament-language-switch/actions?query=workflow%3A%22Check+%26+fix+styling%22+branch%3Amain" class="filament-hidden">
        <img alt="Code Style Passing" src="https://img.shields.io/github/actions/workflow/status/bezhansalleh/filament-language-switch/run-laravel-pint.yml?style=for-the-badge&logo=github&label=code%20style">
    </a>
    <a href="https://packagist.org/packages/bezhansalleh/filament-language-switch">
        <img alt="Downloads" src="https://img.shields.io/packagist/dt/bezhansalleh/filament-language-switch.svg?style=for-the-badge">
    </a>
</p>

# Language Switch

A composable language switching plugin for Filament Panels. Supports dropdown, modal, and inline display modes with smart context detection for topbar, sidebar, and user menu placement.

## Compatibility

| Package Version | Filament Version |
|----------------|------------------|
| [v1](https://github.com/bezhanSalleh/filament-language-switch/tree/1.x) | [v2](https://filamentphp.com/docs/2.x/admin/installation) |
| [v3](https://github.com/bezhanSalleh/filament-language-switch/tree/3.x) | [v3](https://filamentphp.com/docs/3.x/panels/installation) |
| v4 | [v4](https://filamentphp.com/docs/4.x/introduction/overview) & [v5](https://filamentphp.com/docs/5.x/introduction/overview) |
| v5 | [v4](https://filamentphp.com/docs/4.x/introduction/overview) & [v5](https://filamentphp.com/docs/5.x/introduction/overview) |

## Installation

```bash
composer require bezhansalleh/filament-language-switch
```

> [!IMPORTANT]
> Create a custom theme if you haven't already, and add to your `theme.css`:
>
> ```css
> @source '../../../../vendor/bezhansalleh/filament-language-switch/resources/views/**/*.blade.php';
> ```
>
> Then build: `npm run build`

## Quick Start

```php
use BezhanSalleh\LanguageSwitch\LanguageSwitch;

public function boot()
{
    LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch->locales(['ar', 'en', 'fr']);
    });
}
```

That's it. The plugin auto-detects your panel layout and places the trigger appropriately (topbar when enabled, sidebar footer when topbar is off).

## Display Modes

### Dropdown (default)

Opens a dropdown list when the trigger is clicked.

```php
$switch->locales(['ar', 'en', 'fr']);
```

### Modal

Opens a modal dialog. Ideal for many languages or when you want a richer selection experience.

```php
use BezhanSalleh\LanguageSwitch\Enums\DisplayMode;

$switch
    ->locales(['ar', 'en', 'fr', 'de', 'es'])
    ->displayMode(DisplayMode::Modal)
    ->columns(2)
    ->modalWidth('lg');
```

### Modal with Slide-over

```php
$switch
    ->locales(['ar', 'en', 'fr', 'de', 'es'])
    ->displayMode(DisplayMode::Modal)
    ->modalSlideOver();
```

## Trigger Placement

### Inline (User Menu / Sidebar)

Renders the trigger as a native menu item inside the user menu or sidebar, using the universal language icon. Opens dropdown or modal on click.

```php
// Inline dropdown in user menu
$switch
    ->locales(['ar', 'en', 'fr'])
    ->inline();

// Inline modal in user menu
$switch
    ->locales(['ar', 'en', 'fr', 'de', 'es'])
    ->inline()
    ->displayMode(DisplayMode::Modal)
    ->columns(2);
```

### Custom Render Hook

Place the trigger at any Filament render hook:

```php
use Filament\View\PanelsRenderHook;

$switch
    ->locales(['ar', 'en', 'fr'])
    ->renderHook(PanelsRenderHook::SIDEBAR_FOOTER);
```

The trigger automatically adapts its design based on context:

| Hook Context | Trigger Design |
|---|---|
| `TOPBAR_*`, `GLOBAL_SEARCH_*` | Icon button (matches panel-switch) |
| `SIDEBAR_NAV_*` | Sidebar nav item (matches nav items) |
| `SIDEBAR_FOOTER`, `USER_MENU_BEFORE/AFTER` | Sidebar button (matches notifications) |
| `USER_MENU_PROFILE_*` | Dropdown list item (matches menu items) |

### Smart Defaults

When no `renderHook()` is specified, the plugin auto-detects based on `$panel->hasTopbar()`:

| Panel Config | Default Hook |
|---|---|
| Topbar enabled | `GLOBAL_SEARCH_AFTER` |
| Topbar enabled + `inline()` | `USER_MENU_PROFILE_AFTER` |
| Topbar disabled | `USER_MENU_BEFORE` |

## Appearance

### Flags

```php
$switch
    ->locales(['ar', 'en', 'fr'])
    ->flags([
        'ar' => asset('flags/sa.svg'),
        'en' => asset('flags/us.svg'),
        'fr' => asset('flags/fr.svg'),
    ]);
```

### Flags Only

Display only flags without text labels. In dropdown mode, shows compact flag buttons with tooltips. In modal mode, renders a radio-group card grid with flag showcase.

```php
$switch
    ->locales(['ar', 'en', 'fr'])
    ->flags([...])
    ->flagsOnly();
```

### Circular

Makes flags and char avatars fully rounded:

```php
$switch->circular();
```

### Native Labels

Display locale names in their native language:

```php
$switch->nativeLabel();
```

### Custom Labels

```php
$switch->labels([
    'pt_BR' => 'Português (BR)',
    'pt_PT' => 'Português (PT)',
]);
```

### Display Locale

Set the language used for generating locale labels:

```php
$switch->displayLocale('fr');
```

## Modal Configuration

```php
$switch
    ->displayMode(DisplayMode::Modal)
    ->columns(3)                              // grid columns (1 = single column)
    ->modalWidth('2xl')                       // sm, md, lg, xl, 2xl, etc.
    ->modalHeading('Select your language')    // custom heading
    ->modalIcon('heroicon-o-language')        // heading icon
    ->modalIconColor('primary')               // icon color
    ->modalSlideOver();                       // slide-over instead of centered
```

### Flag Height (Modal Flags-Only Cards)

Control the flag image height in modal flag showcase cards:

```php
$switch
    ->flagsOnly()
    ->displayMode(DisplayMode::Modal)
    ->columns(3)
    ->flagHeight('h-16');  // default: 'h-16'. Options: 'h-12', 'h-20', 'h-24'
```

### Char Avatar Height (Modal Cards)

Control the char avatar size in modal radio cards:

```php
$switch
    ->displayMode(DisplayMode::Modal)
    ->columns(2)
    ->charAvatarHeight('size-10');  // default: 'size-8'
```

## Custom Content

Override the locale list or individual item rendering:

```php
// Custom list view
$switch->contentView('my-views.custom-locale-list');

// Custom item view
$switch->itemView('my-views.custom-locale-item');
```

## Dropdown Configuration

### Max Height

```php
$switch->maxHeight('max-h-96');
```

### Dropdown Placement

```php
$switch->dropdownPlacement('top-end');
```

## Panel Exclusion

```php
$switch->excludes(['app']);
```

## Locale Preference

Provide a custom locale resolver (e.g., from user profile):

```php
$switch->userPreferredLocale(fn () => auth()->user()?->locale);
```

## Event

The `LocaleChanged` event is dispatched whenever the locale is switched:

```php
use BezhanSalleh\LanguageSwitch\Events\LocaleChanged;

Event::listen(function (LocaleChanged $event) {
    auth()->user()->update(['locale' => $event->locale]);
});
```

## Views

Publish views for deep customization:

```bash
php artisan vendor:publish --tag="filament-language-switch-views"
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [Bezhan Salleh](https://github.com/bezhanSalleh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

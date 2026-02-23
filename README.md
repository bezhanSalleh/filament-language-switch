<a href="https://github.com/bezhansalleh/filament-language-switch" class="filament-hidden">

![Filament Language Switch](https://repository-images.githubusercontent.com/506847060/81f73ae9-6cef-4f89-a4cf-47de0412e0b5 "Filament Language Switch")

</a>
<p align="center">
    <a href="https://filamentadmin.com/docs/2.x/admin/installation">
        <img alt="FILAMENT 4.x" src="https://img.shields.io/badge/FILAMENT-4.x-EBB304?style=for-the-badge">
    </a>
    <a href="https://packagist.org/packages/bezhansalleh/filament-language-switch">
        <img alt="Packagist" src="https://img.shields.io/packagist/v/bezhansalleh/filament-language-switch.svg?style=for-the-badge&logo=packagist">
    </a>
    <a href="https://github.com/bezhansalleh/filament-language-switch/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain" class="filament-hidden">
        <img alt="Code Style Passing" src="https://img.shields.io/github/actions/workflow/status/bezhansalleh/filament-language-switch/run-laravel-pint.yml?style=for-the-badge&logo=github&label=code%20style">
    </a>

<a href="https://packagist.org/packages/bezhansalleh/filament-language-switch">
    <img alt="Downloads" src="https://img.shields.io/packagist/dt/bezhansalleh/filament-language-switch.svg?style=for-the-badge" >
    </a>
<a href="https://discord.com/channels/883083792112300104/990920249744453642" target="_blank">
    <img alt="Downloads" src="https://img.shields.io/discord/883083792112300104?label=Discord&style=for-the-badge" >
    </a>
</p>

# Language Switch (Extended)

The **Language Switch** plugin is a versatile and user-friendly tool designed for Filament Panels. This extended version introduces powerful new UI capabilities, including **Grid Modals, Card styles, User Menu integration, Content Injection, and advanced button styling**.

#### Compatibility

| Package Version                                                         | Filament Version | 
|-------------------------------------------------------------------------|---------------------|
| [v1](https://github.com/bezhanSalleh/filament-language-switch/tree/1.x) | [v2](https://filamentphp.com/docs/2.x/admin/installation) |
| [v3](https://github.com/bezhanSalleh/filament-language-switch/tree/3.x) | [v3](https://filamentphp.com/docs/3.x/panels/installation) |
| [v4](https://github.com/bezhanSalleh/filament-language-switch/tree/4.x) | [v4](https://filamentphp.com/docs/4.x/introduction/overview) & [v5](https://filamentphp.com/docs/5.x/introduction/overview) |
| v5                                                                      | [v4](https://filamentphp.com/docs/4.x/introduction/overview) & [v5](https://filamentphp.com/docs/5.x/introduction/overview) |

## Installation

Install the package via composer:

```bash
composer require bezhansalleh/filament-language-switch
```
> [!IMPORTANT]
> The plugin follows Filament's theming rules. To use the plugin, create a custom theme if you haven't already, and add the following line to your `theme.css` file:

```css
@source '../../../../vendor/bezhansalleh/filament-language-switch/resources/views/**/*.blade.php';
```
Now build your theme using:
```bash
npm run build
```
--- 

## Quick Start (Modern Configuration)

Get the modern "Icon + Code" trigger that opens a beautiful grid of language cards with just a few lines of configuration in your `AppServiceProvider`:

```php
use BezhanSalleh\LanguageSwitch\LanguageSwitch;

public function boot()
{
    LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
            ->locales(['ar','en','fr'])
            ->nativeLabel(true) // Show "Français" instead of "French"
            
            // UI Configuration
            ->displayAs('modal') 
            ->itemStyle('card') // Display as Cards
            ->modalGridColumns(3) // 3 Columns
            
            // Styling
            ->buttonStyle('transparent') 
            ->languageCodeStyle('minimal')
            ->icon('heroicon-o-language');
    });
}
```

## Features & Screenshots

### 1. Modal & Card Layout
Instead of a simple dropdown list, you can now display languages in a responsive modal with a grid layout. The "Card" style adds a clean border and hover effect.

<!-- PLACEHOLDER: Upload an image of the Modal with Cards here -->
<!-- ![Modal Card Layout](https://your-image-url.com/modal-cards.png) -->
> Configure this using `displayAs('modal')`, `itemStyle('card')`,`->modalWidth('2xl')` and `modalGridColumns(3)`.
<img width="1450" height="1530" alt="Screen shot" src="https://github.com/user-attachments/assets/3888cae6-292f-4d5d-908d-ac229d58f7d8" />

### 2. User Menu Integration
Move the language switcher out of the global search area and tuck it neatly into the user profile menu.

<!-- PLACEHOLDER: Upload an image of the User Menu Dropdown here -->
<!-- ![User Menu Integration](https://your-image-url.com/user-menu.png) -->
> Enable this with `renderAsUserMenuItem(true)`.

### 3. Transparent & Minimal Styling
Create a clean header by removing button borders and backgrounds, showing only the language code or a custom icon.

<!-- PLACEHOLDER: Upload an image of the Transparent Button here -->
<!-- ![Transparent Button](https://your-image-url.com/transparent-btn.png) -->
> Achieve this with `buttonStyle('transparent')`, `languageCodeStyle('minimal')` and `icon('heroicon-o-globe-alt')`.

---

## Configuration Guide

The plugin is organized into logical groups. You can chain these methods in `LanguageSwitch::configureUsing()`.

### 1. Trigger Button Styling
Customize the button users click to open the menu.

| Method | Description |
| :--- | :--- |
| `buttonStyle('outlined')` | Options: `default`, `ghost`, `filled`, `outlined`, `transparent`. |
| `icon('heroicon-o-globe-alt')` | Add a Heroicon before the text. |
| `displayFullLabel(true)` | Show "English" instead of just "EN". |
| `triggerClass('...')` | Add custom CSS classes to the button. |
| `circular(true)` | Make the button fully rounded. |
| `flagsOnly(true)` | Hide text and show flags only. |

### 2. Dropdown & List Items
Customize the look of the list when `displayAs('dropdown')` is used.

| Method | Description |
| :--- | :--- |
| `displayAs('dropdown')` | Default mode. |
| `gridColumns(2)` | Split the dropdown into multiple columns. |
| `dropdownWidth('lg')` | Set the width of the dropdown (e.g., `md`, `lg`, `xl`). |
| `itemStyle('card')` | Options: `list` (default) or `card` (boxed with border). |
| `suggested(['en', 'fr'])` | Pin specific languages to the top of the list. |

### 3. Modal Configuration
Customize the modal when `displayAs('modal')` is used.

| Method | Description |
| :--- | :--- |
| `displayAs('modal')` | Switch to Modal mode. |
| `modalWidth('4xl')` | Set modal size (`sm` to `7xl`). |
| `modalGridColumns(3)` | Grid layout inside the modal. |
| `modalSlideOver(true)` | Slide the modal from the side instead of pop-up. |
| `modalHeading('Select Language')` | Custom header text. |
| `modalAlignment('center')` | Align the modal (Top, Center). |
| `hideLanguageCode(insideModal: true)`| Hide the 'EN' badge inside the cards/list items. |

### 4. User Menu Integration
Integrate directly into the Filament Profile dropdown.

| Method | Description |
| :--- | :--- |
| `renderAsUserMenuItem(true)` | Move switch to Profile dropdown. |
| `userMenuSort(1)` | Reorder the item in the menu. |

### 5. Content Injection (New)
Inject other Blade components or HTML immediately before or after the language switch button. This is perfect for adding a **Dark Mode Toggle** or **Notifications** next to the language switcher.

```php
use Illuminate\Support\Facades\Blade;

$switch
    ->beforeCoreContent(Blade::render('<livewire:light-switch />'))
    ->afterCoreContent('<span>|</span>');
```

---

## Standard Configuration

### Visibility
Control where the switch appears.

```php
$switch
    ->visible(insidePanels: true, outsidePanels: true)
    ->outsidePanelRoutes(['auth.login', 'custom.page'])
    ->excludes(['admin']); // Hide on specific panels
```

### Localized Labels
By default, the plugin uses PHP's native `locale_get_display_name`. You can override this.

```php
$switch
    ->nativeLabel(true) // Use native names (e.g., Español)
    ->labels([
        'pt_BR' => 'Português (Brasil)',
        'zh_CN' => 'Simplified Chinese',
    ]);
```

### Flags
Map locales to image assets.

```php
$switch
    ->flags([
        'ar' => asset('flags/saudi-arabia.svg'),
        'en' => asset('flags/usa.svg'),
    ]);
```

## Translations

You can publish the translations files to customize texts like "Suggested" or "Language":

```bash
php artisan vendor:publish --tag="language-switch-translations"
```

## Views

If you need even deeper HTML customization, you can publish the Blade views:

```bash
php artisan vendor:publish --tag="filament-language-switch-views"
```

## Contributing

If you want to contribute to this package, you may want to test it in a real Filament project:

-   Fork this repository to your GitHub account.
-   Create a Filament app locally.
-   Clone your fork in your Filament app's root directory.
-   In the `/filament-language-switch` directory, create a branch for your fix.

Install the packages in your app's `composer.json`:

```json
"require": {
    "bezhansalleh/filament-language-switch": "dev-fix/branch-name as main-dev",
},
"repositories": [
    {
        "type": "path",
        "url": "filament-language-switch"
    }
]
```

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Bezhan Salleh](https://github.com/bezhanSalleh)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

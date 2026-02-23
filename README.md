<a href="https://github.com/bezhansalleh/filament-language-switch" class="filament-hidden">

![Filament Language Switch](https://repository-images.githubusercontent.com/506847060/81f73ae9-6cef-4f89-a4cf-47de0412e0b5 "Filament Language Switch")

</a>
<p align="center">
    <a href="https://filamentphp.com/docs/5.x/panels/installation">
        <img alt="FILAMENT 4.x & 5.x" src="https://img.shields.io/badge/FILAMENT-4.x%20%26%205.x-EBB304?style=for-the-badge">
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

| Package Version | Filament Version | 
|----------------|---------------------|
| [v1](https://github.com/bezhanSalleh/filament-language-switch/tree/1.x) | [v2](https://filamentphp.com/docs/2.x/admin/installation) |
| [v3](https://github.com/bezhanSalleh/filament-language-switch/tree/3.x) | [v3](https://filamentphp.com/docs/3.x/panels/installation) |
| [v4](https://github.com/bezhanSalleh/filament-language-switch/tree/4.x) | [v4](https://filamentphp.com/docs/4.x/introduction/overview) & [v5](https://filamentphp.com/docs/5.x/introduction/overview) |
| **v5** | **[v4](https://filamentphp.com/docs/4.x/introduction/overview) & [v5](https://filamentphp.com/docs/5.x/introduction/overview)** |

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
            ->nativeLabel(true)
            ->displayAs('modal') 
            ->itemStyle('card') 
            ->modalGridColumns(3) 
            ->buttonStyle('transparent') 
            ->languageCodeStyle('minimal')
            ->icon('heroicon-o-language');
    });
}
```

## Features & Screenshots

### 1. Modal & Card Layout
Instead of a simple dropdown list, display languages in a responsive modal with a grid layout and Card styling.
<img width="100%" alt="Modal Card Layout" src="https://github.com/user-attachments/assets/3888cae6-292f-4d5d-908d-ac229d58f7d8" />

> Configure using `displayAs('modal')`, `itemStyle('card')`, `modalWidth('2xl')` and `modalGridColumns(3)`.

### 2. User Menu Integration
Tuck the language switcher neatly into the native Filament user profile menu with custom sorting.
<img width="400" alt="User Menu Integration" src="https://github.com/user-attachments/assets/b12ee1c4-6645-4a25-9e2a-6d7a43b9c381" />

> Enable with `renderAsUserMenuItem(true)` and `userMenuSort(1)`.

### 3. Transparent & Minimal Styling
Achieve a clean UI with transparent buttons and minimal language badges.
<img width="200" alt="Minimal Styling" src="https://github.com/user-attachments/assets/4a4d3f61-c182-4742-97e9-f76f67ce14ff" />

> Achieve this with `buttonStyle('transparent')`, `languageCodeStyle('minimal')` and `icon('heroicon-o-globe-alt')`.

---

## Configuration Guide

### 1. Trigger Button Styling
Customize the button that opens the menu.

| Method | Description |
| :--- | :--- |
| `buttonStyle('transparent')` | Options: `default`, `ghost`, `filled`, `outlined`, `transparent`. |
| `icon('heroicon-o-language')`| Add a Heroicon to the button. |
| `iconSize('w-5 h-5')` | Custom size for the button icon. |
| `iconPosition('after')` | Position of icon relative to text: `before`, `after`. |
| `flagSize('w-8 h-8')` | Custom size for the flag/language badge. |
| `flagPosition('before')` | Position of flag/code relative to text: `before`, `after`. |
| `languageCodeStyle('minimal')`| Style for the 2-letter badge (e.g. EN). |
| `displayFullLabel(true)` | Show "English" instead of just "EN". |
| `hideLanguageCode(insideModal: true, outsideModal: false)` | Granular control over the EN/AR badge visibility. |

### 2. List & Dropdown Settings
Customize the look of the list when `displayAs('dropdown')` is used.

| Method | Description |
| :--- | :--- |
| `gridColumns(2)` | Split the dropdown menu into multiple columns. |
| `dropdownWidth('md')` | Set dropdown width (e.g., `sm`, `md`, `lg`, `xl`). |
| `itemStyle('card')` | Options: `list` (default) or `card`. |
| `suggested(['en', 'fr'])` | Pin languages to the top with a "Suggested" header. |
| `itemClass('my-class')` | Add custom CSS classes to list items. |

### 3. Modal Configuration
Customize the modal when `displayAs('modal')` is used.

| Method | Description |
| :--- | :--- |
| `modalWidth('4xl')` | Set modal size (`sm` to `7xl`). |
| `modalGridColumns(3)` | Grid layout inside the modal. |
| `modalSlideOver(true)` | Slide the modal from the side. |
| `modalHeading('Select Language')` | Custom translated header text. |
| `modalAlignment('center')` | Align content inside modal (Top, Center). |
| `closeModalByClickingAway(false)` | Control modal dismissal behavior. |

### 4. Content Injection
Inject any Blade component or HTML before or after the language switch button.

```php
use Illuminate\Support\Facades\Blade;

$switch
    ->beforeCoreContent(Blade::render('<livewire:light-switch />'))
    ->afterCoreContent('<div class="w-px h-4 bg-gray-300"></div>');
```

---

## Standard Configuration

### Visibility
```php
$switch
    ->visible(insidePanels: true, outsidePanels: true)
    ->outsidePanelRoutes(['auth.login', 'custom.page'])
    ->excludes(['admin']); 
```

### Localized Labels & Flags
```php
$switch
    ->nativeLabel(true) 
    ->flags([
        'ar' => asset('flags/sa.svg'),
        'en' => asset('flags/us.svg'),
    ])
    ->flagsOnly(); // Hide text, show flags only
```

## Translations
Publish and customize texts like "Suggested" or "Language":
```bash
php artisan vendor:publish --tag="language-switch-translations"
```

## Views
Publish the views for deep HTML customization:
```bash
php artisan vendor:publish --tag="filament-language-switch-views"
```

## Credits
- [Bezhan Salleh](https://github.com/bezhanSalleh)
- [All Contributors](../../contributors)

## License
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

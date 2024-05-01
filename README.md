<a href="https://github.com/bezhansalleh/filament-language-switch" class="filament-hidden">

![Filament Language Switch](./.github/banner.jpg?raw=true "Filament Language Switch")

</a>
<p align="left">
    <a href="https://filamentadmin.com/docs/2.x/admin/installation">
        <img alt="FILAMENT 8.x" src="https://img.shields.io/badge/FILAMENT-2.x-EBB304?style=for-the-badge">
    </a>
    <a href="https://packagist.org/packages/bezhansalleh/filament-language-switch">
        <img alt="Packagist" src="https://img.shields.io/packagist/v/bezhansalleh/filament-language-switch.svg?style=for-the-badge&logo=packagist">
    </a>
    <a href="https://github.com/bezhansalleh/filament-language-switch/actions?query=workflow%3Arun-tests+branch%3Amain" class="filament-hidden">
        <img alt="Tests Passing" src="https://img.shields.io/github/actions/workflow/status/bezhansalleh/filament-language-switch/run-tests.yml?style=for-the-badge&logo=github&label=tests">
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

***

# Language Switch
The **Language Switch** plugin is a versatile and user-friendly tool designed for Filament Panels. It offers a seamless way to integrate language switching capabilities into your Filament Panels. With a range of customizable options and a fluent API, the plugin allows you to easily configure language selection for your users. It supports displaying language options both within and outside of Filament panels, and it provides the flexibility to specify which panels or routes should include the language switch and much more.

  
## Requirement
* [Filament v3](https://filamentphp.com/docs/3.x/panels/installation)

> [!NOTE]  
> For [Filament v2](https://filamentphp.com/docs/2.x/admin/installation) use [v1](https://github.com/bezhanSalleh/filament-language-switch/tree/1.x)

***

> [!IMPORTANT]
> Migrating from 2.x to 3.x
> - Unregister the plugin from all your panels
> - Remove the config file `config/filament-language-switch.php`
> - Remove the `filament-language-switch` directory from `resources/views/vendor`
> - Bump the package version to `^3.0` in your `composer.json` file
> - Run `composer update`
> - Checkout the [Usage](#usage) section below to get up and running. 
> - Checkout the [Configuration](#configuration) section to see what's new and how to configure the plugin.
  
## Installation

Install the package via composer:

```bash
composer require bezhansalleh/filament-language-switch
```

## Usage

The plugin boots after installation automatically. For the plugin to work, provide an array of locales that your Panel(s) support to switch between them inside a service provider's `boot()` method. You can either create a new service provider or use the default `AppServiceProvider` as follow:

```php

...
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;

class AppServiceProvider extends ServiceProvider
{
    ...
    
    public function boot()
    {
        ...
        
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en','fr']); // also accepts a closure
        });
        
        ...
    }
}
```

Though this is all you would need, but the plugin is designed to be very customizable. Checkout the [Laravel localization documentation](https://laravel.com/docs/11.x/localization#introduction) to get started with localization. Delve into the **Configuration** section below for detailed customization options.

## Configuration

The plugin comes with following options that you can customize and configure as per your requirements. The plugin has a fluent API so you can chain the methods and easily configure it all in one place.

### Visibility Control

The `visible()` method configures the visibility of the **Language Switch** within the application's UI. It has two parameters:

- **insidePanels**: 
Determines if the language switcher is visible inside Filament panels, which is `true` by default. The language switcher will be visible inside panels only if the `insidePanels` condition is true, more than one locale is available, and the current panel is included (not excluded).

- **outsidePanels**: Controls visibility outside of the panels, with a default of false. The language switcher will be visible outside of panels only if the `outsidePanels` condition is true, the current route is included in the `outsidePanelRoutes()`, and the current panel is included (not excluded).

Both arguments can be provided as a `boolean` or a `Closure` that returns a boolean value. The Closure enables dynamic determination of visibility, allowing you to incorporate complex logic based on the application state, user permissions, or other criteria.

```php
//AppServiceProvider.php
    ...
    LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
            ...
            ->visible(outsidePanels: true)
            ...;
    });
    ...
```
### Outside Panel Routes

The `outsidePanelRoutes()` method is used to define the routes where the **Language Switch** should be visible outside of the Filament panels. This method accepts either an `array` of route names or a `Closure` that returns an array of route names. By default, it includes common authentication routes such as `auth.login`, `auth.profile`, and `auth.register`.

To specify custom routes for displaying the language switcher, pass an array of route names to the method:

```php
// AppServiceProvider.php
...
LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
    $switch
        ...
        ->outsidePanelRoutes([
            'profile',
            'home',
            // Additional custom routes where the switcher should be visible outside panels
        ])
        ...;
});
...
```

If you want to dynamically determine the routes, use a Closure:

```php
// AppServiceProvider.php
...
LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
    $switch
        ...
        ->outsidePanelRoutes(fn() => someCondition() ? ['dynamic.route'] : ['default.route'])
        ...;
});
...
```

### Outside Panel Placement
The `outsidePanelPlacement()` method specifies the placement of the **Language Switch** when it is rendered outside of Filament panels. This method accepts an `Placement` enum value that determines the switch's position on the screen.

You can choose from the following placements defined in the `Placement` enum:

* `TopLeft` default
* `TopCenter`
* `TopRight`
* `BottomLeft`
* `BottomCenter`
* `BottomRight`
  
Set the desired placement for the **language switch** outside Filament Panels like this:

```php
// AppServiceProvider.php
...
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;

LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
    $switch
        ...
        ->outsidePanelPlacement(Placement::BottomRight)
        // Sets the language switch to appear at the bottom right outside of panels
        ...;
});
...
```
### Localized Labels
The `displayLocale()` method is used to set the locale that influences how labels for given locales are generated by PHP's native function `locale_get_display_name()`. This method specifies the language in which the labels for given locales are displayed when custom labels are not set using the `labels()` method.

By default, if `displayLocale()` is not explicitly set, the locale labels are generated based on the application's current locale. This affects the automatic label generation for locales without custom labels.

For example, if your application's current locale is `English ('en')`, and you have not set a specific display locale, then the labels for locales like `pt_BR` and `pt_PT` would automatically be generated as `Portuguese (Brazil)` and `Portuguese (Portugal)` respectively, in English.

To specify a different language for the automatic label generation, use `displayLocale()`:
```php
// AppServiceProvider.php
...
LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
    $switch
        ...
        ->displayLocale('fr') // Sets French as the language for label localization
        ...;
});
...
```

### Custom Labels

The `labels()` method in the **Language Switch** allows you to define custom text labels for each locale that your application supports. This customization is particularly useful when the default labels generated by PHP's native function `locale_get_display_name()` are not suitable for your application's needs.

By default, if no custom labels are provided, the **Language switch** will generate labels for each locale using the native PHP function `locale_get_display_name()`, which creates a label based on the current application's locale. For example, the locales `pt_BR` and `pt_PT` will be labeled as **Portuguese (Brazil)** and **Portuguese (Portugal)** respectively, when the application's locale is set to `en`.

However, you might prefer to display labels that are shorter or differently formatted. This is where the `labels()` method is beneficial. You can specify exactly how each language should be labeled, overriding the default behavior.

Here's how to set custom labels:

```php
// AppServiceProvider.php
...
LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
    $switch
        ...
        ->locales(['en','fr','pt_BR','pt_PT'])
        ->labels([
            'pt_BR' => 'Português (BR)',
            'pt_PT' => 'Português (PT)',
            // Other custom labels as needed
        ])
        ...;
});
...
```

### Panel Exclusion

By default the **Language Switch** will be available inside all existing Panels. But you can choose which panels will have the switch by providing an array of valid panel ids using the `exclude()` method. The method also accepts a `Closure` so you have more control over how to exclude certain panels.

```php
//AppServiceProvider.php
    ...
    LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
            ...
            ->excludes([
                'app'
            ])
            ...;
    });
    ...
```

### Render Hook

By default the `panels::global-search.after` hook is used to render the **Language Switch**. But you can use any of the [Render Hooks](https://filamentphp.com/docs/3.x/support/render-hooks) available in Filament using the `renderHook()` method as:

```php
//AppServiceProvider.php
    ...
    LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
            ...
            ->renderHook('panels::global-search.before')
            ...;
    });
    ...
```

### Flags

By default the **Language Switch** uses the locales as `Language Badges` to serve as placeholders for the flags. But you may associate each locale with its corresponding flag image by passing an array to the `flags()` method. Each key in the array represents the locale code, and its value should be the asset path to the flag image for that locale. For example, to set flag images for Arabic, French, and English (US), you would provide an array like this:

```php
//AppServiceProvider.php
    ...
    LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
            ...
            ->flags([
                'ar' => asset('flags/saudi-arabia.svg'),
                'fr' => asset('flags/france.svg'),      
                'en' => asset('flags/usa.svg'),
            ])
            ...;
    });
    ...
```

Make sure that the provided paths in the `asset()` helper point to the correct location of the flag images in your Laravel project's public directory.

### Flags Only

The `flagsOnly()` method controls whether the **Language Switch** displays only flag images, without accompanying text labels. This method can enhance the UI by providing a cleaner look when space is limited or when you prefer a more visual representation of language options.

To display only the flags for each language, invoke the method and make sure you have provided the flags for locales just like shown above using the `flags()` method:

```php
//AppServiceProvider.php
    ...
    LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
            ...
            ->flags([
                'ar' => asset('flags/saudi-arabia.svg'),
                'fr' => asset('flags/france.svg'),      
                'en' => asset('flags/usa.svg'),
            ])
            ->flagsOnly()
            ...;
    });
    ...
```

### Circular

By default the **Language Switch** `Flags` or `Language Badges` are slightly rounded like the most other Filament components. But you may make it fully rounded using the `circular()` method.

```php
//AppServiceProvider.php
    ...
    LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
        $switch
            ...
            ->circular()
            ...;
    });
    ...
```

## Theme
The plugin follows Filament's theming rules. So, if you have custom themes add the plugin's view path into the `content` array of your themes' `tailwind.config.js` file:

```php
//tailwind.config.js
export default {
    content: [
        // ...
        './vendor/bezhansalleh/filament-language-switch/resources/views/language-switch.blade.php',
    ],
    // ...
}
```
... now build your theme using: 
```bash
npm run build
```

## Views
In case you want to tweak the design, you can publish the views using the following command and adjust it however you like:

```bash
php artisan vendor:publish --tag="filament-language-switch-views"
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

If you want to contribute to this package, you may want to test it in a real Filament project:

-   Fork this repository to your GitHub account.
-   Create a Filament app locally.
-   Clone your fork in your Filament app's root directory.
-   In the `/filament-language-switch` directory, create a branch for your fix, e.g. `fix/error-message`.

Install the packages in your app's `composer.json`:

```json
"require": {
    "bezhansalleh/filament-language-switch": "dev-fix/error-message as main-dev",
},
"repositories": [
    {
        "type": "path",
        "url": "filament-language-switch"
    }
]
```

Now, run `composer update`.

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

-   [Bezhan Salleh](https://github.com/bezhanSalleh)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

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
</p>

<hr style="background-color: #ff2e21"></hr>

# Language(Locale) Switch

Zero config Language Switch(Changer/Localizer) plugin for Filament Panels.

  
## Requirement
* [Filament v3](https://filamentphp.com/docs/3.x/panels/installation)

> [!NOTE]  
> For [Filament v2](https://filamentphp.com/docs/2.x/admin/installation) use [v1](https://github.com/bezhanSalleh/filament-language-switch/tree/1.x)

  
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

Though this is all you would need, but the plugin is designed to be very customizable. Delve into the **Configuration** section below for detailed customization options.

## Configuration

The plugin comes with following options that you can customize and configure as per your requirements. The plugin has a fluent API so you can chain the methods and easily configure it all in one place.

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
                ->locales(['ar','fr','en'])
                ->visible(insidePanels: true, outsidePanels: false)
                ->renderHook('panels::global-search.before')
                ->outsidePanelRoutes() // default: ['auth.login','auth.profile','auth.register']
                ->excludes([])
                ->displayLocale() // returns an appropriately localized display name for the input locale based on the current app locale
                ->labels([ // override the displayLocale and provide custom display name(label) for the locale
                    'ar' => 'عربی',
                    'en' => 'انگلیسی',
                    'fr' => 'فرانسوی'
                ])
                ->circular()
                ->flags([
                    'ar' => asset('flags/saudi-arabia.svg'),
                    'fr' => asset('flags/france.svg'),
                    'en' => asset('flags/usa.svg'),
                ])
                ->flagsOnly()
                ;
        });
        ...
    }
}
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

If you want to contribute to this packages, you may want to test it in a real Filament project:

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

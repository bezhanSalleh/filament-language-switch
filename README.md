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

# Filament Language(Locale) Switcher

Zero config Language Switch(Changer/Localizer) plugin for Filamentphp Admin

* For Filamentphp 2.x use version 1.x

## Installation

Install the package via composer:

```bash
composer require bezhansalleh/filament-language-switch
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="filament-language-switch-config"
```

Configure your preferred options and then register the plugin to your panel(s).

> **Note**
> You can find the supported country flag codes here [flag codes](https://flagicons.lipis.dev/)

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-language-switch-views"
```

## Plugin Usage
Using the plugin is easy all you need to do is instanciate it to the `Panels` you want the plugin to be available in.
```php
public function panel(Panel $panel): Panel
{
    return $panel
        ...
        ->plugins([
            FilamentLanguageSwitchPlugin::make()
        ])
        ...
}
```
## Customize Render Hook

By default the switch render in the `panels::global-search.after` hook but you can render the **Language Switch** in any of the [Render Hooks](https://beta.filamentphp.com/docs/3.x/panels/configuration#render-hooks) available in Filamentphp using the `renderHookName()` method inside your panel's `plugins()` method.

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ...
        ->plugins([
            FilamentLanguageSwitchPlugin::make()
                ->renderHookName('panels::global-search.before'),
        ])
        ...
}
```

## Custom Theme
By default the plugin uses the default theme of Filamentphp, but you can customize it by adding the plugins view path into the `content` array of your `tailwind.config.js` file:

```php
export default {
    content: [
        // ...
        './vendor/bezhansalleh/filament-language-switch/resources/views/language-switch.blade.php',
    ],
    // ...
}
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

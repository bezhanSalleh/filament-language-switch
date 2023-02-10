
<a href="https://github.com/bezhansalleh/filament-language-switch">
<img style="width: 100%; max-width: 100%;" alt="filament-shield-art" src="https://banners.beyondco.de/Filament%20Language%20Switch.png?theme=light&packageManager=composer+require&packageName=bezhansalleh%2Ffilament-language-switch&pattern=topography&style=style_2&description=Zero+config+Language+Switcher+for+Filamentphp&md=1&showWatermark=0&fontSize=125px&images=translate" >
</a>
<p align="center">
    <a href="https://filamentadmin.com/docs/2.x/admin/installation">
        <img alt="FILAMENT 8.x" src="https://img.shields.io/badge/FILAMENT-2.x-EBB304?style=for-the-badge">
    </a>
    <a href="https://packagist.org/packages/bezhansalleh/filament-language-switch">
        <img alt="Packagist" src="https://img.shields.io/packagist/v/bezhansalleh/filament-language-switch.svg?style=for-the-badge&logo=packagist">
    </a>
    <a href="https://github.com/bezhansalleh/filament-language-switch/actions?query=workflow%3Arun-tests+branch%3Amain">
        <img alt="Tests Passing" src="https://img.shields.io/github/actions/workflow/status/bezhansalleh/filament-language-switch/run-tests.yml?style=for-the-badge&logo=github&label=tests">
    </a>
    <a href="https://github.com/bezhansalleh/filament-language-switch/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain">
        <img alt="Code Style Passing" src="https://img.shields.io/github/actions/workflow/status/bezhansalleh/filament-language-switch/run-laravel-pint.yml?style=for-the-badge&logo=github&label=code%20style">
    </a>

<a href="https://packagist.org/packages/bezhansalleh/filament-language-switch">
    <img alt="Downloads" src="https://img.shields.io/packagist/dt/bezhansalleh/filament-language-switch.svg?style=for-the-badge" >
    </a>
</p>

<hr style="background-color: #ff2e21"></hr>

# Filament Language(Locale) Switcher

Zero config Language Switch(Changer/Localizer) plugin for Filamentphp Admin

## Installation

Install the package via composer:

```bash
composer require bezhansalleh/filament-language-switch
```

Publish the config file with:

```bash
php artisan vendor:publish --tag="filament-language-switch-config"
```

Set your preferred options:

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Carbon Locale(Language)
    |--------------------------------------------------------------------------
    |
    | Option to whether change the language for carbon library or not.
    |
    */
    'carbon' => true,

    /*
    |--------------------------------------------------------------------------
    | Language display name
    |--------------------------------------------------------------------------
    |
    | Option to whether display the language in English or Native.
    |
    */
    'native' => true,

/*
    |--------------------------------------------------------------------------
    | Flag
    |--------------------------------------------------------------------------
    |
    | Option to display flag for the Language.
    | By default the first and second letter of the display name (if single word, otherwise first letter of first two words) will be used instead of flag.
    | If set to true, the following package needs to be installed via composer.
    | "composer require stijnvanouplines/blade-country-flags"
    */
    
    'flag' => false,

    /*
    |--------------------------------------------------------------------------
    | All Locales (Languages)
    |--------------------------------------------------------------------------
    |
    | Uncomment the languages that your site supports - or add new ones.
    | These are sorted by the native name, which is the order you might show them in a language selector.
    |
    */

    'locales' => [
        'ar'          => ['name' => 'Arabic',                 'script' => 'Arab', 'native' => 'العربية', 'flag_code' => 'sa'],
        'en'          => ['name' => 'English',                'script' => 'Latn', 'native' => 'English', 'flag_code' => 'us'],
        // 'fr'          => ['name' => 'French',                 'script' => 'Latn', 'native' => 'français', 'flag_code' => 'fr'],

        // 'ace'         => ['name' => 'Achinese',               'script' => 'Latn', 'native' => 'Aceh', 'flag_code' => '' ],
        //'af'          => ['name' => 'Afrikaans',              'script' => 'Latn', 'native' => 'Afrikaans', 'flag_code' => '' ],
        //'agq'         => ['name' => 'Aghem',                  'script' => 'Latn', 'native' => 'Aghem', 'flag_code' => '' ],
        //'ak'          => ['name' => 'Akan',                   'script' => 'Latn', 'native' => 'Akan', 'flag_code' => '' ],
        //'an'          => ['name' => 'Aragonese',              'script' => 'Latn', 'native' => 'aragonés', 'flag_code' => '' ],
        //'cch'         => ['name' => 'Atsam',                  'script' => 'Latn', 'native' => 'Atsam', 'flag_code' => '' ],
        //'gn'          => ['name' => 'Guaraní',                'script' => 'Latn', 'native' => 'Avañe’ẽ', 'flag_code' => '' ],
        //'ae'          => ['name' => 'Avestan',                'script' => 'Latn', 'native' => 'avesta', 'flag_code' => '' ],
        //'ay'          => ['name' => 'Aymara',                 'script' => 'Latn', 'native' => 'aymar aru', 'flag_code' => '' ],
        //'az'          => ['name' => 'Azerbaijani (Latin)',    'script' => 'Latn', 'native' => 'azərbaycanca', 'flag_code' => '' ],
        ...
    ]
];
     
```
That's it, everything is now ready to be deployed.

> **Note**
> You can find the supported country flag codes here [flag codes](https://flagicons.lipis.dev/)



Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-language-switch-views"
```


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

If you want to contribute to this packages, you may want to test it in a real Filament project:

- Fork this repository to your GitHub account.
- Create a Filament app locally.
- Clone your fork in your Filament app's root directory.
- In the `/filament-language-switch` directory, create a branch for your fix, e.g. `fix/error-message`.

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

- [Bezhan Salleh](https://github.com/bezhanSalleh)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

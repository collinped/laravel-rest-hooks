# laravel-rest-hooks

[![Latest Version on Packagist](https://img.shields.io/packagist/v/collinped/laravel-rest-hooks.svg?style=flat-square)](https://packagist.org/packages/collinped/laravel-rest-hooks)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/collinped/laravel-rest-hooks/run-tests?label=tests)](https://github.com/collinped/laravel-rest-hooks/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/collinped/laravel-rest-hooks.svg?style=flat-square)](https://packagist.org/packages/collinped/laravel-rest-hooks)

Package for managing [Rest Hooks](https://resthooks.org) in Laravel for use with platforms such as [Zapier](https://zapier.com/).

## Installation

You can install the package via composer:

```bash
composer require collinped/laravel-rest-hooks
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Collinped\LaravelRestHooks\LaravelRestHooksServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Collinped\LaravelRestHooks\LaravelRestHooksServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

``` php
$laravel-rest-hooks = new Collinped\LaravelRestHooks();
echo $laravel-rest-hooks->echoPhrase('Hello, Collinped!');
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email me@collinped.com instead of using the issue tracker.

## Credits

- [Collin Pedersen](https://github.com/collinped)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

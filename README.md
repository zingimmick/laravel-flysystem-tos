# Laravel Flysystem TOS

TOS storage filesystem for Laravel based on [zing/flysystem-tos](https://github.com/zingimmick/flysystem-tos)

[![Build Status](https://github.com/zingimmick/laravel-flysystem-tos/actions/workflows/tests.yml/badge.svg?branch=2.x)](https://github.com/zingimmick/laravel-flysystem-tos/actions)
[![Code Coverage](https://codecov.io/gh/zingimmick/laravel-flysystem-tos/branch/2.x/graph/badge.svg)](https://codecov.io/gh/zingimmick/laravel-flysystem-tos)
[![Latest Stable Version](https://poser.pugx.org/zing/laravel-flysystem-tos/v/stable.svg)](https://packagist.org/packages/zing/laravel-flysystem-tos)
[![Total Downloads](https://poser.pugx.org/zing/laravel-flysystem-tos/downloads)](https://packagist.org/packages/zing/laravel-flysystem-tos)
[![Latest Unstable Version](https://poser.pugx.org/zing/laravel-flysystem-tos/v/unstable.svg)](https://packagist.org/packages/zing/laravel-flysystem-tos)
[![License](https://poser.pugx.org/zing/laravel-flysystem-tos/license)](https://packagist.org/packages/zing/laravel-flysystem-tos)

> **Requires**
> - **[PHP 8.0+](https://php.net/releases/)**
> - **[Laravel 9.0+](https://laravel.com/docs/releases)**

## Version Information

| Version | Illuminate | PHP Version | Status                  |
|:--------|:-----------|:------------|:------------------------|
| 2.x     | 9.x        | >= 8.0      | Active support :rocket: |
| 1.x     | 6.x - 8.x  | >= 7.2      | Active support          |

Require Laravel Flysystem TOS using [Composer](https://getcomposer.org):

```bash
composer require zing/laravel-flysystem-tos
```

## Configuration

```php
return [
    // ...
    'disks' => [
        // ...
        'tos' => [
            'driver' => 'tos',
            'root' => '',
            'access_key_id' => env('TOS_ACCESS_KEY_ID'),
            'access_key_secret' => env('TOS_ACCESS_KEY_SECRET'),
            'bucket' => env('TOS_BUCKET'),
            'endpoint' => env('TOS_ENDPOINT'),
            'is_cname' => env('TOS_IS_CNAME', false),
            'security_token' => env('TOS_SECURITY_TOKEN'),
        ],
    ]
];
```

## Environment

```dotenv
TOS_ACCESS_KEY_ID=
TOS_ACCESS_KEY_SECRET=
TOS_BUCKET=
TOS_ENDPOINT=
TOS_IS_CNAME=false
TOS_SECURITY_TOKEN=
```

## License

Laravel Flysystem TOS is an open-sourced software licensed under the [MIT license](LICENSE).

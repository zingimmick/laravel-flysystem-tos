# Laravel Flysystem TOS

<p align="center">
<a href="https://github.com/zingimmick/laravel-flysystem-tos/actions"><img src="https://github.com/zingimmick/laravel-flysystem-tos/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://codecov.io/gh/zingimmick/laravel-flysystem-tos"><img src="https://codecov.io/gh/zingimmick/laravel-flysystem-tos/branch/master/graph/badge.svg" alt="Code Coverage" /></a>
<a href="https://packagist.org/packages/zing/laravel-flysystem-tos"><img src="https://poser.pugx.org/zing/laravel-flysystem-tos/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-flysystem-tos"><img src="https://poser.pugx.org/zing/laravel-flysystem-tos/downloads" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/zing/laravel-flysystem-tos"><img src="https://poser.pugx.org/zing/laravel-flysystem-tos/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://packagist.org/packages/zing/laravel-flysystem-tos"><img src="https://poser.pugx.org/zing/laravel-flysystem-tos/license" alt="License"></a>
</p>

> **Requires**
> - **[PHP 7.2+](https://php.net/releases/)**
> - **[Laravel 6.0+](https://github.com/laravel/laravel)**

## Version Information

| Version | Illuminate | PHP Version | Status                  |
|:--------|:-----------|:------------|:------------------------|
| 2.x     | 9.x        | >= 8.0      | Active support :rocket: |
| 1.x     | 6.x - 8.x  | >= 7.2      | Active support          |

Require Laravel Flysystem TOS using [Composer](https://getcomposer.org):

```bash
composer require zing/laravel-flysystem-tos:^1.0
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
            'ak' => env('TOS_AK'),
            'sk' => env('TOS_SK'),
            'region' => env('TOS_REGION'),
            'bucket' => env('TOS_BUCKET'),
            'endpoint' => env('TOS_ENDPOINT'),
            'securityToken' => env('TOS_SECURITY_TOKEN'),
        ],
    ]
];
```

## Environment

```dotenv
TOS_AK=
TOS_SK=
TOS_REGION=
TOS_BUCKET=
TOS_ENDPOINT=
TOS_IS_CNAME=false
TOS_SECURITY_TOKEN=
```

## License

Laravel Flysystem TOS is an open-sourced software licensed under the [MIT license](LICENSE).

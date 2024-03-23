<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Tos\Tests;

use Illuminate\Support\Facades\Config;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Zing\LaravelFlysystem\Tos\TosServiceProvider;

abstract class TestCase extends BaseTestCase
{
    /**
     * @param mixed $app
     *
     * @return array<class-string<\Illuminate\Support\ServiceProvider>>
     */
    protected function getPackageProviders($app): array
    {
        return [TosServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app): void
    {
        Config::set('filesystems.disks.tos', [
            'driver' => 'tos',
            'key' => 'aW52YWxpZC1rZXk=',
            'secret' => 'aW52YWxpZC1zZWNyZXQ=',
            'region' => 'cn-beijing',
            'bucket' => 'your-bucket',
            'endpoint' => 'your-endpoint',
        ]);
        Config::set('filesystems.disks.tos-url', [
            'driver' => 'tos',
            'key' => 'aW52YWxpZC1rZXk=',
            'secret' => 'aW52YWxpZC1zZWNyZXQ=',
            'region' => 'cn-beijing',
            'bucket' => 'your-bucket',
            'endpoint' => 'your-endpoint',
            'url' => 'https://test-url',
        ]);
        Config::set('filesystems.disks.tos-temporary-url', [
            'driver' => 'tos',
            'key' => 'aW52YWxpZC1rZXk=',
            'secret' => 'aW52YWxpZC1zZWNyZXQ=',
            'region' => 'cn-beijing',
            'bucket' => 'your-bucket',
            'endpoint' => 'your-endpoint',
            'temporary_url' => 'https://test-temporary-url',
        ]);
        Config::set('filesystems.disks.tos-bucket-endpoint', [
            'driver' => 'tos',
            'key' => 'aW52YWxpZC1rZXk=',
            'secret' => 'aW52YWxpZC1zZWNyZXQ=',
            'region' => 'cn-beijing',
            'bucket' => 'your-bucket',
            'endpoint' => 'https://your-endpoint',
            'bucket_endpoint' => true,
        ]);
        Config::set('filesystems.disks.tos-is-cname', [
            'driver' => 'tos',
            'key' => 'aW52YWxpZC1rZXk=',
            'secret' => 'aW52YWxpZC1zZWNyZXQ=',
            'region' => 'cn-beijing',
            'bucket' => 'your-bucket',
            'endpoint' => 'https://your-endpoint',
            'is_cname' => true,
        ]);
    }
}

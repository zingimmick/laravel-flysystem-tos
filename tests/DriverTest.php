<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Tos\Tests;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Zing\Flysystem\Tos\TosAdapter;

/**
 * @internal
 */
final class DriverTest extends TestCase
{
    public function testDriverRegistered(): void
    {
        self::assertInstanceOf(TosAdapter::class, Storage::disk('tos')->getDriver()->getAdapter());
    }

    public function testUrl(): void
    {
        self::assertStringStartsWith('https://test-url', Storage::disk('tos-url')->url('test'));
    }

    public function testTemporaryUrl(): void
    {
        self::assertStringStartsWith(
            'https://test-temporary-url',
            Storage::disk('tos-temporary-url')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }

    public function testBucketEndpoint(): void
    {
        self::assertStringStartsWith('https://your-endpoint', Storage::disk('tos-bucket-endpoint')->url('test'));
    }

    public function testIsCname(): void
    {
        self::markTestSkipped('Custom domain names are not supported.');
        self::assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('tos-bucket-endpoint')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
        self::assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('tos-is-cname')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }
}

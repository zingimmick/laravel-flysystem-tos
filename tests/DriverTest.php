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
        $this->assertInstanceOf(TosAdapter::class, Storage::disk('tos')->getDriver()->getAdapter());
    }

    public function testUrl(): void
    {
        $this->assertStringStartsWith('https://test-url', Storage::disk('tos-url')->url('test'));
    }

    public function testTemporaryUrl(): void
    {
        $this->assertStringStartsWith(
            'https://test-temporary-url',
            Storage::disk('tos-temporary-url')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }

    public function testBucketEndpoint(): void
    {
        $this->assertStringStartsWith('https://your-endpoint', Storage::disk('tos-bucket-endpoint')->url('test'));
    }

    public function testIsCname(): void
    {
        $this->assertStringStartsWith('https://your-endpoint', Storage::disk('tos-bucket-endpoint')->url('test'));
        $this->assertStringStartsWith('https://your-endpoint', Storage::disk('tos-is-cname')->url('test'));
        $this->assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('tos-bucket-endpoint')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
        $this->assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('tos-is-cname')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }
}

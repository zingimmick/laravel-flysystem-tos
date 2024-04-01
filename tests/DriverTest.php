<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Tos\Tests;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToWriteFile;
use Zing\Flysystem\Tos\TosAdapter;

/**
 * @internal
 */
final class DriverTest extends TestCase
{
    public function testDriverRegistered(): void
    {
        $this->assertInstanceOf(TosAdapter::class, Storage::disk('tos')->getAdapter());
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
        $this->assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('tos-bucket-endpoint')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
        $this->assertStringStartsWith(
            'https://your-endpoint',
            Storage::disk('tos-is-cname')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }

    public function testReadOnly(): void
    {
        $this->expectException(UnableToWriteFile::class);
        Storage::disk('tos-read-only')->write('test', 'test');
    }

    public function testPrefix(): void
    {
        $this->assertSame(
            'https://your-bucket.your-endpoint/root/prefix/test',
            Storage::disk('tos-prefix-url')->url('test')
        );
        $this->assertStringStartsWith(
            'https://your-bucket.your-endpoint/root/prefix/test',
            Storage::disk('tos-prefix-url')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
    }

    public function testReadOnlyAndPrefix(): void
    {
        $this->assertSame(
            'https://your-bucket.your-endpoint/root/prefix/test',
            Storage::disk('tos-read-only-and-prefix-url')->url('test')
        );
        $this->assertStringStartsWith(
            'https://your-bucket.your-endpoint/root/prefix/test',
            Storage::disk('tos-read-only-and-prefix-url')->temporaryUrl('test', Carbon::now()->addMinutes())
        );
        $this->expectException(UnableToWriteFile::class);
        Storage::disk('tos-read-only-and-prefix-url')->write('test', 'test');
    }

    public function testTemporaryUploadUrl(): void
    {
        $now = Carbon::createFromTimestamp('1679168447');
        $temporaryUploadUrl = Storage::disk('tos-temporary-url')->temporaryUploadUrl('test', $now);
        $this->assertSame([], $temporaryUploadUrl['headers']);
        $uri = new Uri($temporaryUploadUrl['url']);
        $this->assertSame('https', $uri->getScheme());
        $this->assertSame('test-temporary-url', $uri->getHost());
        $this->assertSame('/test', $uri->getPath());
        $query = explode('&', $uri->getQuery());
        asort($query);

        $this->assertEmpty(array_diff(
            [
                'X-Tos-Algorithm=TOS4-HMAC-SHA256',
                'X-Tos-Credential=aW52YWxpZC1rZXk%3D%2F20240401%2Fcn-beijing%2Ftos%2Frequest',
                'X-Tos-SignedHeaders=host',
            ],
            array_values($query)
        ));
    }
}

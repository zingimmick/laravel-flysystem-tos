<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Tos;

use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use League\Flysystem\PathPrefixing\PathPrefixedAdapter;
use League\Flysystem\ReadOnly\ReadOnlyFilesystemAdapter;
use League\Flysystem\Visibility;
use Tos\TosClient;
use Zing\Flysystem\Tos\PortableVisibilityConverter;
use Zing\Flysystem\Tos\TosAdapter as Adapter;

/**
 * ServiceProvider for TOS
 */
class TosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('tos', static function ($app, $config): FilesystemAdapter {
            $root = $config['root'] ?? '';
            $options = $config['options'] ?? [];
            $portableVisibilityConverter = new PortableVisibilityConverter(
                $config['visibility'] ?? Visibility::PUBLIC,
                $config['directory_visibility'] ?? $config['visibility'] ?? Visibility::PUBLIC
            );
            $config['key'] ??= $config['ak'] ?? '';
            $config['ak'] = $config['key'];
            $config['secret'] ??= $config['sk'] ?? '';
            $config['sk'] = $config['secret'];
            $config['bucket_endpoint'] ??= $config['is_cname'] ?? false;
            $config['is_cname'] = $config['bucket_endpoint'];
            $config['token'] ??= $config['security_token'] ?? null;
            $config['securityToken'] = $config['token'];
            $options = array_merge(
                $options,
                Arr::only($config, ['url', 'temporary_url', 'endpoint', 'bucket_endpoint'])
            );
            $tosClient = new TosClient($config);
            $tosAdapter = new Adapter(
                $tosClient,
                $config['bucket'],
                $root,
                $portableVisibilityConverter,
                null,
                $options
            );
            $adapter = $tosAdapter;
            if (($config['read-only'] ?? false) === true) {
                $adapter = new ReadOnlyFilesystemAdapter($adapter);
            }

            if (! empty($config['prefix'])) {
                $adapter = new PathPrefixedAdapter($adapter, $config['prefix']);
            }

            return new TosAdapter(new Filesystem($adapter, $config), $tosAdapter, $config, $tosClient);
        });
    }
}

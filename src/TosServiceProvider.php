<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Tos;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\Filesystem;
use Tos\TosClient;
use Zing\Flysystem\Tos\Plugins\FileUrl;
use Zing\Flysystem\Tos\Plugins\Kernel;
use Zing\Flysystem\Tos\Plugins\SetBucket;
use Zing\Flysystem\Tos\Plugins\SignUrl;
use Zing\Flysystem\Tos\Plugins\TemporaryUrl;
use Zing\Flysystem\Tos\TosAdapter;

class TosServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Storage::extend('tos', static function ($app, $config): Filesystem {
            $root = $config['root'] ?? '';
            $options = $config['options'] ?? [];
            $config['key'] = $config['key'] ?? $config['ak'] ?? '';
            $config['ak'] = $config['key'];
            $config['secret'] = $config['secret'] ?? $config['sk'] ?? '';
            $config['sk'] = $config['secret'];
            $config['bucket_endpoint'] = $config['bucket_endpoint'] ?? $config['is_cname'] ?? false;
            $config['is_cname'] = $config['bucket_endpoint'];
            $config['token'] = $config['token'] ?? $config['security_token'] ?? null;
            $config['securityToken'] = $config['token'];
            $options = array_merge(
                $options,
                Arr::only($config, ['url', 'temporary_url', 'endpoint', 'bucket_endpoint'])
            );
            $tosAdapter = new TosAdapter(new TosClient($config), $config['bucket'], $root, $options);
            $filesystem = new Filesystem($tosAdapter, $config);
            $filesystem->addPlugin(new FileUrl());
            $filesystem->addPlugin(new SignUrl());
            $filesystem->addPlugin(new TemporaryUrl());
            $filesystem->addPlugin(new SetBucket());
            $filesystem->addPlugin(new Kernel());

            return $filesystem;
        });
    }
}

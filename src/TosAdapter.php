<?php

declare(strict_types=1);

namespace Zing\LaravelFlysystem\Tos;

use GuzzleHttp\Psr7\Uri;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Traits\Conditionable;
use League\Flysystem\FilesystemOperator;
use Tos\Model\PreSignedURLInput;
use Tos\TosClient;
use Zing\Flysystem\Tos\TosAdapter as Adapter;

class TosAdapter extends FilesystemAdapter
{
    use Conditionable;

    /**
     * @var \Zing\Flysystem\Tos\TosAdapter
     */
    protected $adapter;

    /**
     * @param array<string, mixed> $config
     */
    public function __construct(
        FilesystemOperator $driver,
        Adapter $adapter,
        array $config,
        protected TosClient $tosClient
    ) {
        parent::__construct($driver, $adapter, $config);
    }

    /**
     * Get the URL for the file at the given path.
     *
     * @param string $path
     */
    public function url($path): string
    {
        if (isset($this->config['url'])) {
            return $this->concatPathToUrl($this->config['url'], $this->prefixer->prefixPath($path));
        }

        $alternativeEndpoint = $this->config['url'] ?? '';
        if ($this->config['bucket_endpoint'] ?? false) {
            $alternativeEndpoint = $this->config['endpoint'];
        }

        $uri = new Uri($this->signUrl($this->prefixer->prefixPath($path), 0, [], 'GET', $alternativeEndpoint));

        return (string) $uri->withQuery('');
    }

    /**
     * Determine if temporary URLs can be generated.
     */
    public function providesTemporaryUrls(): bool
    {
        return true;
    }

    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param string $path
     * @param \DateTimeInterface $expiration
     * @param array<string, mixed> $options
     */
    public function temporaryUrl($path, $expiration, array $options = []): string
    {
        $alternativeEndpoint = $this->config['temporary_url'] ?? '';
        if ($this->config['bucket_endpoint'] ?? false) {
            $alternativeEndpoint = $this->config['endpoint'];
        }

        $uri = new Uri($this->signUrl(
            $this->prefixer->prefixPath($path),
            $expiration,
            $options,
            'GET',
            $alternativeEndpoint
        ));

        if (isset($this->config['temporary_url'])) {
            $uri = $this->replaceBaseUrl($uri, $this->config['temporary_url']);
        }

        return (string) $uri;
    }

    /**
     * Get the underlying S3 client.
     */
    public function getClient(): TosClient
    {
        return $this->tosClient;
    }

    /**
     * Get a signed URL for the file at the given path.
     *
     * @param array<string, mixed> $options
     */
    public function signUrl(
        string $path,
        \DateTimeInterface|int $expiration,
        array $options = [],
        string $method = 'GET',
        string $alternativeEndpoint = ''
    ): string {
        $expires = $expiration instanceof \DateTimeInterface ? $expiration->getTimestamp() - time() : $expiration;

        $preSignedURLInput = new PreSignedURLInput(
            $method,
            $alternativeEndpoint === '' || $alternativeEndpoint === '0' ? $this->config['bucket'] : '',
            $path,
            $expires
        );
        if ($alternativeEndpoint !== '' && $alternativeEndpoint !== '0') {
            $preSignedURLInput->setAlternativeEndpoint($alternativeEndpoint);
        }

        $preSignedURLInput->setQuery($options);

        return $this->tosClient->preSignedURL($preSignedURLInput)
            ->getSignedUrl();
    }

    /**
     * Get a temporary URL for the file at the given path.
     *
     * @param string $path
     * @param \DateTimeInterface $expiration
     * @param array<string, mixed> $options
     *
     * @return array{url: string, headers: never[]}
     */
    public function temporaryUploadUrl($path, $expiration, array $options = []): array
    {
        $alternativeEndpoint = $this->config['temporary_url'] ?? '';
        if ($this->config['bucket_endpoint'] ?? false) {
            $alternativeEndpoint = $this->config['endpoint'];
        }

        $uri = new Uri($this->signUrl(
            $this->prefixer->prefixPath($path),
            $expiration,
            $options,
            'PUT',
            $alternativeEndpoint
        ));

        if (isset($this->config['temporary_url'])) {
            $uri = $this->replaceBaseUrl($uri, $this->config['temporary_url']);
        }

        return [
            'url' => (string) $uri,
            'headers' => [],
        ];
    }
}

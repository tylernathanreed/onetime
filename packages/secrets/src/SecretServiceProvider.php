<?php

namespace Reedware\Secrets;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Reedware\Secrets\Contracts\SecretCompressor;
use Reedware\Secrets\Contracts\SecretEncrypter;
use Reedware\Secrets\Contracts\SecretKeyGenerator;
use Reedware\Secrets\Contracts\SecretManager;
use Reedware\Secrets\Contracts\SecretRepository;

class SecretServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerSecretKeyGenerator();
        $this->registerSecretRepository();
        $this->registerSecretCompressor();
        $this->registerSecretEncrypter();
        $this->registerSecretManager();
    }

    /**
     * Registers the secret key generator.
     */
    protected function registerSecretKeyGenerator(): void
    {
        $this->app->singleton(SecretKeyGenerator::class, function () {
            return new RandomSlugGenerator;
        });
    }

    /**
     * Registers the secret repository.
     */
    protected function registerSecretRepository(): void
    {
        $this->app->singleton(SecretRepository::class, function ($app) {
            $repository = $app->make('cache')->store('secrets');

            $config = $app->make('config');

            if (is_array($limits = $config->get('cache.stores.secrets.limits'))) {
                $maxCount = $limits['count'] ?? null;
                $maxLength = $limits['length'] ?? null;
            }

            return new CacheRepository(
                $repository,
                $maxCount ?? null,
                $maxLength ?? null
            );
        });
    }

    /**
     * Registers the secret compressor.
     */
    protected function registerSecretCompressor(): void
    {
        $this->app->singleton(SecretCompressor::class, function () {
            return new GzCompressor;
        });
    }

    /**
     * Registers the secret encrypter.
     */
    protected function registerSecretEncrypter(): void
    {
        $this->app->singleton(SecretEncrypter::class, function ($app) {
            return new CompressionEncrypter(
                $app->make('encrypter'),
                $app->make(SecretCompressor::class)
            );
        });
    }

    /**
     * Registers the secret manager.
     */
    protected function registerSecretManager(): void
    {
        $this->app->singleton(SecretManager::class, function ($app) {
            return new Manager(
                $app->make(SecretKeyGenerator::class),
                $app->make(SecretRepository::class),
                $app->make(SecretEncrypter::class),
                $app->make('events')
            );
        });

        $this->app->alias(SecretManager::class, 'secrets');
    }

    /**
     * Returns the services provided by the provider.
     */
    public function provides()
    {
        return [
            SecretCompressor::class,
            SecretEncrypter::class,
            SecretManager::class
        ];
    }
}

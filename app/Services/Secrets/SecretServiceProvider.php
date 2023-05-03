<?php

namespace App\Services\Secrets;

use App\Services\Secrets\Contracts\SecretCompressor;
use App\Services\Secrets\Contracts\SecretEncrypter;
use App\Services\Secrets\Contracts\SecretKeyGenerator;
use App\Services\Secrets\Contracts\SecretManager;
use App\Services\Secrets\Contracts\SecretRepository;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laravel\Octane\Cache\OctaneStore;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        $this->registerRouterBinding();
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

            if ($repository->getStore() instanceof OctaneStore) {
                $config = $app->make('config');

                $maxCount = $config->get('octane.cache.rows');
                $maxLength = $config->get('octane.cache.bytes');
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
                $app->make(SecretEncrypter::class)
            );
        });

        $this->app->alias(SecretManager::class, 'secrets');
    }

    /**
     * Registers the router binding.
     */
    protected function registerRouterBinding(): void
    {
        $this->app->afterResolving('router', function (Router $router, $app) {
            $router->bind('secret', function ($slug) use ($app) {
                if (! $app->make(SecretManager::class)->has($slug)) {
                    throw new NotFoundHttpException('Your secret does not exist, or has expired.');
                }

                return $slug;
            });
        });
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

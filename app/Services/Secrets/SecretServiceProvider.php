<?php

namespace App\Services\Secrets;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use App\Services\Secrets\Contracts\Factory as FactoryContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SecretServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Register the secret factory
        $this->app->singleton(FactoryContract::class, function($app) {
            return new Factory(
                $app['cache']->store('octane'),
                $app['encrypter'],
                $app['config']->get('octane.cache.rows'),
                $app['config']->get('octane.cache.bytes')
            );
        });

        // Alias the secret factory
        $this->app->alias(FactoryContract::class, 'secrets');

        // Ensure the secret exists when routed
        $this->app['router']->bind('secret', function($slug) {
            if(!app(FactoryContract::class)->has($slug)) {
                throw new NotFoundHttpException('Your secret does not exist, or has expired.');
            }

            return $slug;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            FactoryContract::class,
        ];
    }
}

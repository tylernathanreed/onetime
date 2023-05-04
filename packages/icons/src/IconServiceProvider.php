<?php

namespace Reedware\Icons;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Reedware\Icons\Contracts\Manager as ManagerContract;

class IconServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->app->singleton(ManagerContract::class, function ($app) {
            return new Manager(
                $app['cache']->store('icons'),
                $app['view']
            );
        });

        $this->app->alias(ManagerContract::class, Manager::class);
        $this->app->alias(ManagerContract::class, 'icons');
    }

    /**
     * Boots the provided services.
     */
    public function boot()
    {
        Blade::directive('icon', function ($name) {
            return "<?php echo app('icons')->icon({$name}); ?>";
        });
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides()
    {
        return [
            ManagerContract::class
        ];
    }
}

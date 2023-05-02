<?php

namespace App\Services\Icons;

use App\Services\Icons\Contracts\Manager as ManagerContract;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\View\Factory;

class Manager implements ManagerContract
{
    /**
     * Creates a new manager instance.
     */
    public function __construct(
        protected Repository $cache,
        protected Factory $view
    ) {
       // 
    }

    /**
     * Returns the html for the specified icon.
     */
    public function icon(string $name): string
    {
        return $this->cache->rememberForever('icons.' . $name, function () use ($name) {
            return $this->resolve($name);
        });
    }

    /**
     * Resolves the html for the specified icon.
     */
    protected function resolve(string $name): string
    {
        return $this->view->make('icons.' . $name)->render();
    }

    /**
     * Returns the cache implementation.
     */
    public function getCache(): Repository
    {
        return $this->cache;
    }

    /**
     * Returns the view factory implementation.
     */
    public function getViewFactory(): Factory
    {
        return $this->view;
    }
}

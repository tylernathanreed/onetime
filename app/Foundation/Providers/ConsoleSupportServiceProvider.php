<?php

namespace App\Foundation\Providers;

use Illuminate\Database\MigrationServiceProvider;
use Illuminate\Support\AggregateServiceProvider;
use Illuminate\Foundation\Providers\ComposerServiceProvider;

class ConsoleSupportServiceProvider extends AggregateServiceProvider
{
    /**
     * The provider class names.
     *
     * @var string[]
     */
    protected $providers = [
        ArtisanServiceProvider::class,
        ComposerServiceProvider::class,
    ];
}

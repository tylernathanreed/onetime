<?php

namespace App\Foundation\Providers;

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
        ArtisanServiceProvider::class, // We're using our own here, as we don't need all commands
        // MigrationServiceProvider::class, // No database, no database commands
        ComposerServiceProvider::class,
    ];
}

<?php

namespace App\Foundation\Octane;

use Laravel\Octane\Octane as Foundation;

class Octane
{
    /**
     * Returns the listeners that will prepare the Laravel application for a new request.
     */
    public static function prepareApplicationForNextRequest(): array
    {
        return static::except(Foundation::prepareApplicationForNextRequest(), [
            \Laravel\Octane\Listeners\FlushQueuedCookies::class,
            \Laravel\Octane\Listeners\FlushAuthenticationState::class,
            \Laravel\Octane\Listeners\GiveNewRequestInstanceToPaginator::class
        ]);
    }

    /**
     * Returns the listeners that will prepare the Laravel application for a new operation.
     */
    public static function prepareApplicationForNextOperation(): array
    {
        return static::except(Foundation::prepareApplicationForNextOperation(), [
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToAuthorizationGate::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToBroadcastManager::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToDatabaseManager::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToDatabaseSessionHandler::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToMailManager::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToNotificationChannelManager::class,
            \Laravel\Octane\Listeners\GiveNewApplicationInstanceToQueueManager::class,
            \Laravel\Octane\Listeners\FlushDatabaseRecordModificationState::class,
            \Laravel\Octane\Listeners\FlushDatabaseQueryLog::class,
            \Laravel\Octane\Listeners\RefreshQueryDurationHandling::class,
            \Laravel\Octane\Listeners\FlushArrayCache::class,
            \Laravel\Octane\Listeners\FlushStrCache::class,

            // First-Party Packages...
            \Laravel\Octane\Listeners\PrepareInertiaForNextOperation::class,
            \Laravel\Octane\Listeners\PrepareLivewireForNextOperation::class,
            \Laravel\Octane\Listeners\PrepareScoutForNextOperation::class,
            \Laravel\Octane\Listeners\PrepareSocialiteForNextOperation::class,
        ]);
    }

    /**
     * Get the container bindings / services that should be pre-resolved by default.
     *
     * @return array
     */
    public static function defaultServicesToWarm(): array
    {
        return static::except(Foundation::defaultServicesToWarm(), [
            'auth',
            'db',
            'db.factory',
            'db.transactions',
            'hash'
        ]);
    }

    /**
     * Disables the specified listeners / services.
     */
    protected static function except(array $default, array $values): array
    {
        return array_filter($default, function (string $value) use ($values) {
            return ! in_array($value, $values);
        });
    }
}

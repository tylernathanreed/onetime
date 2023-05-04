<?php

namespace App\Services\Secrets\Events;

class SecretCreated
{
    /**
     * Creates a new event instance.
     */
    public function __construct(
        public readonly string $key
    ) {
        //
    }
}

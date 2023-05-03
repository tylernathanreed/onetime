<?php

namespace App\Services\Secrets\Contracts;

interface SecretKeyGenerator
{
    /**
     * Returns a new identifier for the specified secret.
     */
    public function generate(string $secret): string;
}

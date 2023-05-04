<?php

namespace Reedware\Secrets\Contracts;

interface SecretKeyGenerator
{
    /**
     * Returns a new identifier for the specified secret.
     */
    public function generate(string $secret): string;
}

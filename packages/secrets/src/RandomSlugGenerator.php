<?php

namespace Reedware\Secrets;

use Illuminate\Support\Str;
use Reedware\Secrets\Contracts\SecretKeyGenerator;

class RandomSlugGenerator implements SecretKeyGenerator
{
    /**
     * Returns a new identifier for the specified secret.
     */
    public function generate(string $secret): string
    {
        return substr(Str::slug(Str::random(48)), 0, 32);
    }
}

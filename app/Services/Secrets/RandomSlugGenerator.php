<?php

namespace App\Services\Secrets;

use App\Services\Secrets\Contracts\SecretKeyGenerator;
use Illuminate\Support\Str;

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

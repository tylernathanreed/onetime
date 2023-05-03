<?php

namespace App\Services\Secrets\Contracts;

interface SecretCompressor
{
    /**
     * Compresses the specified string.
     */
    public function compress(string $data): string;

    /**
     * Decompresses the specified string.
     */
    public function decompress(string $data): string;
}

<?php

namespace Reedware\Secrets;

use Reedware\Secrets\Contracts\SecretCompressor;

class GzCompressor implements SecretCompressor
{
    /**
     * Compresses the specified string.
     */
    public function compress(string $data): string
    {
        return gzcompress($data);
    }

    /**
     * Decompresses the specified string.
     */
    public function decompress(string $data): string
    {
        return gzuncompress($data);
    }
}

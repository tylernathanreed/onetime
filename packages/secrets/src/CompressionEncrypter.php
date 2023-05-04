<?php

namespace Reedware\Secrets;

use Illuminate\Contracts\Encryption\Encrypter;
use Reedware\Secrets\Contracts\SecretCompressor;
use Reedware\Secrets\Contracts\SecretEncrypter;

class CompressionEncrypter implements SecretEncrypter
{
    /**
     * Creates a new encrypter instance.
     */
    public function __construct(
        protected Encrypter $encrypter,
        protected SecretCompressor $compressor
    ) {
        //
    }

    /**
     * Encrypts the specified secret.
     */
    public function encrypt(string $secret): string
    {
        return $this->encrypter->encrypt(
            $this->compressor->compress($secret)
        );
    }

    /**
     * Decrypts the specified secret.
     */
    public function decrypt(string $secret): string
    {
        return $this->compressor->decompress(
            $this->encrypter->decrypt($secret)
        );
    }

    /**
     * Returns the encrypter implementation.
     */
    public function getEncrypter(): Encrypter
    {
        return $this->encrypter;
    }

    /**
     * Returns the compresser implementation.
     */
    public function getCompressor(): SecretCompressor
    {
        return $this->compressor;
    }
}

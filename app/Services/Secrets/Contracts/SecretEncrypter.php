<?php

namespace App\Services\Secrets\Contracts;

interface SecretEncrypter
{
    /**
     * Encrypts the specified secret.
     */
    public function encrypt(string $secret): string;

    /**
     * Decrypts the specified secret.
     */
    public function decrypt(string $secret): string;
}

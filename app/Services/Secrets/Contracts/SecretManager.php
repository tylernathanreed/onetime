<?php

namespace App\Services\Secrets\Contracts;

use DateTimeInterface;

interface SecretManager
{
    /**
     * Returns whether or not the specified secret exists.
     */
    public function has(string $slug): bool;

    /**
     * Returns the specified secret.
     */
    public function get(string $slug): ?string;

    /**
     * Stores the specified secret and returns its identifer.
     */
    public function store(string $secret, DateTimeInterface|string|null $ttl = null);

    /**
     * Deletes the specified secret.
     */
    public function delete(string $slug): bool;

    /**
     * Returns the secret key generator implementation.
     */
    public function getKeyGenerator(): SecretKeyGenerator;

    /**
     * Returns the secret repository implementation.
     */
    public function getRepository(): SecretRepository;

    /**
     * Returns the encrypter implementation.
     */
    public function getEncrypter(): SecretEncrypter;
}

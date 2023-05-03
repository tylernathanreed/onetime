<?php

namespace App\Services\Secrets\Contracts;

use DateTimeInterface;

interface SecretRepository
{
    /**
     * Returns whether or not the specified secret exists.
     */
    public function has(string $slug): bool;

    /**
     * Returns and destroys the specified secret.
     */
    public function pull(string $slug): ?string;

    /**
     * Stores the specified secret with the given ttl.
     */
    public function put(string $key, string $secret, DateTimeInterface|string|null $ttl = null): void;

    /**
     * Destroys the specified secret.
     */
    public function forget(string $slug): bool;

    /**
     * Returns the maximum number of secrets that can be stored.
     */
    public function getMaxCount(): ?int;

    /**
     * Returns the maximum length allowed for any single secret.
     */
    public function getMaxLength(): ?int;
}

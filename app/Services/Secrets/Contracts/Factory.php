<?php

namespace App\Services\Secrets\Contracts;

use DateTimeInterface;

interface Factory
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
     * Returns the maximum number of secrets that can be stored.
     */
    public function rowLimit(): ?int;

    /**
     * Returns the maximum byte length allowed for a single secret.
     */
    public function byteLimit(): ?int;
}

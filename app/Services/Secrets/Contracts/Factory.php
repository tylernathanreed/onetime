<?php

namespace App\Services\Secrets\Contracts;

interface Factory
{
    /**
     * Returns whether or not the specified secret exists.
     *
     * @param  string  $slug
     *
     * @return boolean
     */
    public function has(string $slug);

    /**
     * Returns the specified secret.
     *
     * @param  string  $slug
     *
     * @return string|null
     */
    public function get(string $slug);

    /**
     * Stores the specified secret and returns its identifer.
     *
     * @param  string  $secret
     * @param  \DateTimeInterface|\DateInterval|string|int|null  $ttl
     *
     * @return string
     */
    public function store(string $secret, $ttl = null);

    /**
     * Deletes the specified secret.
     *
     * @param  string  $slug
     *
     * @return boolean
     */
    public function delete(string $slug);
}

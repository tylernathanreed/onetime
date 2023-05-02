<?php

namespace App\Services\Secrets;

use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Str;
use App\Services\Secrets\Contracts\Factory as FactoryContract;
use DateTimeInterface;

class Factory implements FactoryContract
{
    /**
     * The cache repository implementation.
     */
    protected Repository $cache;

    /**
     * The encrypter implementation.
     */
    protected Encrypter $encrypter;

    /**
     * The maximum number of secrets that can be stored.
     */
    protected ?int $rowLimit;

    /**
     * The maximum byte length allowed for a single secret.
     */
    protected ?int $byteLimit;

    /**
     * Creates a new secret factory.
     */
    public function __construct(Repository $cache, Encrypter $encrypter, int $rowLimit = null, int $byteLimit = null)
    {
        $this->cache = $cache;
        $this->encrypter = $encrypter;
        $this->rowLimit = $rowLimit;
        $this->byteLimit = $byteLimit;
    }

    /**
     * Returns whether or not the specified secret exists.
     */
    public function has(string $slug): bool
    {
        return $this->cache->has($slug);
    }

    /**
     * Returns the specified secret.
     */
    public function get(string $slug): ?string
    {
        // Determine the secret
        $secret = $this->cache->pull($slug);

        // If the secret didn't exist, return null
        if(is_null($secret)) {
            return null;
        }

        // Return the decrypted secret
        return $this->decrypt($secret);
    }

    /**
     * Stores the specified secret and returns its identifer.
     */
    public function store(string $secret, DateTimeInterface|string|null $ttl = null): string
    {
        // Generate a secret identifer
        $identifer = $this->identifer();

        // If the TTL is a string, parse it
        if(is_string($ttl)) {
            $ttl = Carbon::parse($ttl);
        }

        // Encrypt the secret
        $secret = $this->encrypt($secret);

        // Store the secret
        $this->cache->put($identifer, $secret, $ttl);

        // Return the identifer
        return $identifer;
    }

    /**
     * Deletes the specified secret.
     */
    public function delete(string $slug): bool
    {
        return $this->cache->forget($slug);
    }

    /**
     * Encrypts the specified secret.
     */
    protected function encrypt(string $secret): string
    {
        return $this->encrypter->encrypt(gzcompress($secret));
    }

    /**
     * Decrypts the specified secret.
     */
    protected function decrypt(string $secret): string
    {
        return gzuncompress($this->encrypter->decrypt($secret));
    }

    /**
     * Creatse and returns a new secret identifer.
     */
    protected function identifer(): string
    {
        return substr(Str::slug(Str::random(48)), 0, 32);
    }

    /**
     * Returns the maximum number of secrets that can be stored.
     */
    public function rowLimit(): ?int
    {
        return $this->rowLimit;
    }

    /**
     * Returns the maximum byte length allowed for a single secret.
     */
    public function byteLimit(): ?int
    {
        return $this->byteLimit;
    }
}

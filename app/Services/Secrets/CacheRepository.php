<?php

namespace App\Services\Secrets;

use App\Services\Secrets\Contracts\SecretRepository;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Contracts\Cache\Repository;

class CacheRepository implements SecretRepository
{
    /**
     * Creates a new secret factory.
     */
    public function __construct(
        protected Repository $cache,
        protected ?int $maxCount = null,
        protected ?int $maxLength = null
    ) {
        //
    }

    /**
     * Returns whether or not the specified secret exists.
     */
    public function has(string $slug): bool
    {
        return $this->cache->has($slug);
    }

    /**
     * Returns and destroys the specified secret.
     */
    public function pull(string $slug): ?string
    {
        return $this->cache->pull($slug);
    }

    /**
     * Stores the specified secret with the given ttl.
     */
    public function put(string $key, string $secret, DateTimeInterface|string|null $ttl = null): void
    {
        if (is_string($ttl)) {
            $ttl = Carbon::parse($ttl);
        }

        $this->cache->put($key, $secret, $ttl);
    }

    /**
     * Destroys the specified secret.
     */
    public function forget(string $slug): bool
    {
        return $this->cache->forget($slug);
    }

    /**
     * Returns the maximum number of secrets that can be stored.
     */
    public function getMaxCount(): ?int
    {
        return $this->maxCount;
    }

    /**
     * Returns the maximum length allowed for any single secret.
     */
    public function getMaxLength(): ?int
    {
        return $this->maxLength;
    }

    /**
     * Returns the cache repository implementation.
     */
    public function getCache(): Repository
    {
        return $this->cache;
    }
}

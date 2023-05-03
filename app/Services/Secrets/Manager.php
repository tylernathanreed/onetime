<?php

namespace App\Services\Secrets;

use App\Services\Secrets\Contracts\SecretEncrypter;
use App\Services\Secrets\Contracts\SecretKeyGenerator;
use App\Services\Secrets\Contracts\SecretManager;
use App\Services\Secrets\Contracts\SecretRepository;
use DateTimeInterface;

class Manager implements SecretManager
{
    /**
     * Creates a new secret factory.
     */
    public function __construct(
        protected SecretKeyGenerator $generator,
        protected SecretRepository $repository,
        protected SecretEncrypter $encrypter
    ) {
        //
    }

    /**
     * Returns whether or not the specified secret exists.
     */
    public function has(string $slug): bool
    {
        return $this->repository->has($slug);
    }

    /**
     * Returns the specified secret.
     */
    public function get(string $slug): ?string
    {
        $secret = $this->repository->pull($slug);

        if (is_null($secret)) {
            return null;
        }

        return $this->encrypter->decrypt($secret);
    }

    /**
     * Stores the specified secret and returns its identifer.
     */
    public function store(string $secret, DateTimeInterface|string|null $ttl = null): string
    {
        $key = $this->generator->generate($secret);

        $secret = $this->encrypter->encrypt($secret);

        $this->repository->put($key, $secret, $ttl);

        return $key;
    }

    /**
     * Deletes the specified secret key.
     */
    public function delete(string $key): bool
    {
        return $this->repository->forget($key);
    }

    /**
     * Returns the secret key generator implementation.
     */
    public function getKeyGenerator(): SecretKeyGenerator
    {
        return $this->generator;
    }

    /**
     * Returns the secret repository implementation.
     */
    public function getRepository(): SecretRepository
    {
        return $this->repository;
    }

    /**
     * Returns the encrypter implementation.
     */
    public function getEncrypter(): SecretEncrypter
    {
        return $this->encrypter;
    }
}

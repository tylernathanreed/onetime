<?php

namespace App\Services\Secrets;

use App\Services\Secrets\Contracts\SecretEncrypter;
use App\Services\Secrets\Contracts\SecretKeyGenerator;
use App\Services\Secrets\Contracts\SecretManager;
use App\Services\Secrets\Contracts\SecretRepository;
use App\Services\Secrets\Events\SecretCreated;
use App\Services\Secrets\Events\SecretDestroyed;
use App\Services\Secrets\Events\SecretRetrieved;
use DateTimeInterface;
use Illuminate\Contracts\Events\Dispatcher;

class Manager implements SecretManager
{
    /**
     * Creates a new secret factory.
     */
    public function __construct(
        protected SecretKeyGenerator $generator,
        protected SecretRepository $repository,
        protected SecretEncrypter $encrypter,
        protected Dispatcher $events
    ) {
        //
    }

    /**
     * Returns whether or not the specified secret exists.
     */
    public function has(string $key): bool
    {
        return $this->repository->has($key);
    }

    /**
     * Returns the specified secret.
     */
    public function get(string $key): ?string
    {
        $secret = $this->repository->pull($key);

        if (is_null($secret)) {
            return null;
        }

        $secret = $this->encrypter->decrypt($secret);

        $this->events->dispatch(new SecretRetrieved($key));

        return $secret;
    }

    /**
     * Stores the specified secret and returns its identifer.
     */
    public function store(string $secret, DateTimeInterface|string|null $ttl = null): string
    {
        $key = $this->generator->generate($secret);

        $secret = $this->encrypter->encrypt($secret);

        $this->repository->put($key, $secret, $ttl);

        $this->events->dispatch(new SecretCreated($key));

        return $key;
    }

    /**
     * Deletes the specified secret key.
     */
    public function delete(string $key): bool
    {
        $response = $this->repository->forget($key);

        $this->events->dispatch(new SecretDestroyed($key));

        return $response;
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

    /**
     * Returns the event dispatcher.
     */
    public function getDispatcher(): Dispatcher
    {
        return $this->events;
    }
}

<?php

namespace App\Services\Secrets;

use Carbon\Carbon;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Str;
use App\Services\Secrets\Contracts\Factory as FactoryContract;

class Factory implements FactoryContract
{
    /**
     * The cache repository implementation.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * The encrypter implementation.
     *
     * @var \Illuminate\Contracts\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * The maximum number of secrets that can be stored.
     *
     * @var int|null
     */
    protected $rowLimit;

    /**
     * The maximum byte length allowed for a single secret.
     *
     * @var int|null
     */
    protected $byteLimit;

    /**
     * Creates a new secret factory.
     *
     * @param  \Illuminate\Contracts\Cache\Repository      $cache
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     * @param  int|null                                    $rowLimit
     * @param  int|null                                    $byteLimit
     *
     * @return $this
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
     *
     * @param  string  $slug
     *
     * @return boolean
     */
    public function has($slug)
    {
        return $this->cache->has($slug);
    }

    /**
     * Returns the specified secret.
     *
     * @param  string  $slug
     *
     * @return string|null
     */
    public function get($slug)
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
     *
     * @param  string  $secret
     * @param  \DateTimeInterface|\DateInterval|string|int|null  $ttl
     *
     * @return string
     */
    public function store($secret, $ttl = null)
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
     *
     * @param  string  $slug
     *
     * @return boolean
     */
    public function delete($slug)
    {
        return $this->cache->forget($slug);
    }

    /**
     * Encrypts the specified secret.
     *
     * @param  string  $secret
     *
     * @return string
     */
    protected function encrypt($secret)
    {
        return $this->encrypter->encrypt(gzcompress($secret));
    }

    /**
     * Decrypts the specified secret.
     *
     * @param  string  $secret
     *
     * @return string
     */
    protected function decrypt($secret)
    {
        return gzuncompress($this->encrypter->decrypt($secret));
    }

    /**
     * Creatse and returns a new secret identifer.
     *
     * @return string
     */
    protected function identifer()
    {
        return substr(Str::slug(Str::random(48)), 0, 32);
    }

    /**
     * Returns the maximum number of secrets that can be stored.
     *
     * @return int|null
     */
    public function rowLimit()
    {
        return $this->rowLimit;
    }

    /**
     * Returns the maximum byte length allowed for a single secret.
     *
     * @return int|null
     */
    public function byteLimit()
    {
        return $this->byteLimit;
    }
}

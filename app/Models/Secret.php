<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Secret extends Model
{
    use HasFactory;

    /**
     * The maximum length a secret can be.
     *
     * @var integer
     */
    const MAX_LENGTH = 10000;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function isExpired()
    {
        return !is_null($this->expires_at) && $this->expires_at->isPast();
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed        $value
     * @param  string|null  $field
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Find the secret
        $secret = $this->where($field ?: $this->getRouteKeyName(), $value)->firstOrFail();

        // Check if the secret has expired
        if($secret->isExpired()) {

            // Delete the secret
            $secret->delete();

            // Throw an exception
            throw (new ModelNotFoundException)->setModel(static::class, [$value]);

        }

        // Return the secret
        return $secret;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Returns the name of the "updated at" column.
     *
     * @return string|null
     */
    public function getUpdatedAtColumn()
    {
        return null;
    }
}

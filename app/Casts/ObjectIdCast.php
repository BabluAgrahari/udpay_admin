<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use MongoDB\BSON\ObjectId;

class ObjectIdCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, $key, $value, $attributes)
    {
        if ($value instanceof ObjectId) {
            return (string) $value;
        }
        
        return $value;
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, $key, $value, $attributes)
    {
        if (empty($value)) {
            return null;
        }
        
        if ($value instanceof ObjectId) {
            return $value;
        }
        
        if (is_string($value) && preg_match('/^[a-f\d]{24}$/i', $value)) {
            return new ObjectId($value);
        }
        
        return $value;
    }
} 
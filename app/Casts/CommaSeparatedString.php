<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class CommaSeparatedString implements CastsAttributes
{
    /**
     * Cast the given value.
     */
    public function get($model, $key, $value, $attributes)
    {
        if (blank($value)) {
            return [];
        }

        return is_array($value) ? $value : array_filter(explode(',', $value));
    }

    /**
     * Prepare the given value for storage.
     */
    public function set($model, $key, $value, $attributes)
    {
        if (is_array($value)) {
            return implode(',', array_filter($value));
        }

        return $value;
    }
}

<?php

namespace Isswp101\Persimmon\Traits;

trait Fillable
{
    /**
     * Fill the instance with an array of attributes.
     *
     * @param array $attributes
     * @return $this
     */
    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
        return $this;
    }
}

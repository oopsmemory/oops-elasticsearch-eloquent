<?php

namespace Isswp101\Persimmon\Traits;

trait Arrayable
{
    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
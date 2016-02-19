<?php

namespace Isswp101\Persimmon\Support;

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
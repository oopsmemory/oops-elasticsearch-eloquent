<?php

namespace Isswp101\Persimmon\Traits;

trait Stringable
{
    use Jsonable;

    /**
     * Get the instance as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }
}
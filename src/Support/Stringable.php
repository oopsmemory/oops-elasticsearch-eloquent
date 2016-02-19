<?php

namespace Isswp101\Persimmon\Support;

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
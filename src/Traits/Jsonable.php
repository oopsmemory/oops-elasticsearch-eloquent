<?php

namespace Isswp101\Persimmon\Traits;

trait Jsonable
{
    use Arrayable;

    /**
     * Get the instance as json.
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
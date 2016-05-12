<?php

namespace Isswp101\Persimmon\Traits;

use Illuminate\Support\Collection;

trait Presentable
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

    /**
     * Get the instance as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Get the instance as a collection.
     *
     * @return Collection
     */
    public function toCollection()
    {
        return collect($this->toArray());
    }
}
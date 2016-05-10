<?php

namespace Isswp101\Persimmon;

use Isswp101\Persimmon\Contracts\Arrayable;
use Isswp101\Persimmon\Contracts\Jsonable;
use Isswp101\Persimmon\Contracts\Stringable;
use Isswp101\Persimmon\Traits\Cacheable;
use Isswp101\Persimmon\Traits\Containerable;
use Isswp101\Persimmon\Traits\Fillable;
use Isswp101\Persimmon\Traits\Idable;
use Isswp101\Persimmon\Traits\Presentable;
use Isswp101\Persimmon\Traits\Timestampable;
use Isswp101\Persimmon\Traits\Userable;

abstract class Model implements Arrayable, Jsonable, Stringable
{
    use Idable, Userable, Timestampable;
    use Fillable, Cacheable, Containerable;
    use Presentable;

    /**
     * Create a new instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->initContainer();

        $this->fill($attributes);
    }

    public function toArray()
    {
        return array_where(get_object_vars($this), function ($key) {
            return !starts_with($key, '_');
        });
    }

    abstract public function save();
}
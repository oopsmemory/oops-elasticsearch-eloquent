<?php

namespace Isswp101\Persimmon;

use Isswp101\Persimmon\Traits\Arrayable;
use Isswp101\Persimmon\Traits\Cacheable;
use Isswp101\Persimmon\Traits\Containerable;
use Isswp101\Persimmon\Traits\Fillable;
use Isswp101\Persimmon\Traits\Idable;
use Isswp101\Persimmon\Traits\Jsonable;
use Isswp101\Persimmon\Traits\Stringable;
use Isswp101\Persimmon\Traits\Timestampable;
use Isswp101\Persimmon\Traits\Userable;

class Model
{
    use Idable;
    use Userable;
    use Timestampable;

    use Fillable;
    use Cacheable;
    use Containerable;

    use Jsonable;
    use Arrayable;
    use Stringable;

    /**
     * Create a new instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes)
    {
        $this->fill($attributes);
    }
}
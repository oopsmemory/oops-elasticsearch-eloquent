<?php

namespace Isswp101\Persimmon;

use Isswp101\Persimmon\Support\Arrayable;
use Isswp101\Persimmon\Support\Cacheable;
use Isswp101\Persimmon\Support\Containerable;
use Isswp101\Persimmon\Support\Fillable;
use Isswp101\Persimmon\Support\Idable;
use Isswp101\Persimmon\Support\Jsonable;
use Isswp101\Persimmon\Support\Stringable;
use Isswp101\Persimmon\Support\Timestampable;
use Isswp101\Persimmon\Support\Userable;

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
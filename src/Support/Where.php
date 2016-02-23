<?php

namespace Isswp101\Persimmon\Support;

class Where
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var bool
     */
    public $strict;

    /**
     * Create a new instance.
     *
     * @param string $key
     * @param mixed $value
     * @param bool $strict
     */
    public function __construct($key, $value, $strict)
    {
        $this->key = $key;
        $this->value = $value;
        $this->strict = $strict;
    }
}
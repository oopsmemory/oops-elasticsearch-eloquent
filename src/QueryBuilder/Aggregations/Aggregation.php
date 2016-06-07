<?php

namespace Isswp101\Persimmon\QueryBuilder\Aggregations;

abstract class Aggregation
{
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    abstract public function make();
}

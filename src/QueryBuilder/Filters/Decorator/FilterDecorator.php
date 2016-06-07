<?php

namespace Isswp101\Persimmon\QueryBuilder\Filters\Filter\Decorator;

use Isswp101\Persimmon\QueryBuilder\Filters\Filter;

abstract class FilterDecorator
{
    protected $filter;

    public function __construct(Filter $filter)
    {
        $this->filter = $filter;
    }

    public function __call($method, $args)
    {
        return call_user_func_array(array($this->filter, $method), $args);
    }
}

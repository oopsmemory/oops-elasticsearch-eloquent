<?php

namespace Isswp101\Persimmon\QueryBuilder\Filters\Filter\Decorator;

use Isswp101\Persimmon\QueryBuilder\Filters\Filter;

class HasParentDecorator extends FilterDecorator
{
    protected $parentType;

    public function __construct(Filter $filter, $parentType)
    {
        parent::__construct($filter);
        $this->parentType = $parentType;
    }

    public function query($values)
    {
        $query = [
            'has_parent' => [
                'type' => $this->parentType,
                'filter' => $this->filter->query($values),
            ]
        ];
        return $query;
    }
}

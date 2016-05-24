<?php

namespace Isswp101\Persimmon\QueryBuilder\Filters;

class TermFilter extends Filter
{
    public function __construct($key, $value)
    {
        parent::__construct([$key => $value]);
    }

    public function query($values)
    {
        $filter = is_array(head($values)) ? 'terms' : 'term';

        $query = [
            $filter => $values
        ];

        return $query;
    }
}

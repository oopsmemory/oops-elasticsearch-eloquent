<?php

namespace Isswp101\Persimmon\QueryBuilder\Filters;

class IdsFilter extends Filter
{
    public function query($values)
    {
        $query = [
            'ids' => [
                'values' => $values
            ]
        ];
        return $query;
    }
}
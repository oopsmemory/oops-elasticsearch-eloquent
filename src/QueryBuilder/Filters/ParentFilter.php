<?php

namespace Isswp101\Persimmon\QueryBuilder\Filters;

class ParentFilter extends Filter
{
    public function __construct($ids)
    {
        $ids = is_array($ids) ? $ids : [$ids];

        parent::__construct($ids);
    }

    public function query($values)
    {
        $query = [
            'terms' => [
                '_parent' => $values
            ]
        ];
        return $query;
    }
}

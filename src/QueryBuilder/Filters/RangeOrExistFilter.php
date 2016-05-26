<?php

namespace Isswp101\Persimmon\QueryBuilder\Filters;

class RangeOrExistFilter extends Filter
{
    protected $field;

    public function __construct($field, $values = null)
    {
        parent::__construct($values);

        $this->field = $field;
    }

    public function query($range)
    {
        if ($range === null) {
            $query = [
                'exists' => [
                    'field' => $this->field
                ]
            ];
        } else {
            $query = [
                'range' => [
                    $this->field => $range
                ]
            ];
        }
        return $query;
    }
}

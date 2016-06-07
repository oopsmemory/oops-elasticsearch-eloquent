<?php

namespace Isswp101\Persimmon\QueryBuilder\Aggregations;

use Isswp101\Persimmon\Traits\Presentable;

class Bucket
{
    use Presentable;

    public $key;
    public $doc_count = 0;

    public function __construct($key, $doc_count)
    {
        $this->key = $key;
        $this->doc_count = $doc_count;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->doc_count;
    }
}

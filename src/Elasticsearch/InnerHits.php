<?php

namespace Isswp101\Persimmon\Elasticsearch;

class InnerHits
{
    protected $hits = [];

    public function __construct(array $response)
    {
        $this->hits = array_get($response, 'inner_hits', []);
    }

    public function getParentId($parentType)
    {
        return array_get($this->hits, 'inner_hits.' . $parentType . '.hits.hits.0._id');
    }

    public function getParent($parentType)
    {
        return array_get($this->hits, 'inner_hits.' . $parentType . '.hits.hits.0');
    }

    public function get()
    {
        return $this->hits;
    }
}

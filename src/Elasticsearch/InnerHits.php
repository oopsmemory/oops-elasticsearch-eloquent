<?php

namespace Isswp101\Persimmon\Elasticsearch;

use Illuminate\Support\Arr;

class InnerHits
{
    protected $hits = [];

    public function __construct(array $response)
    {
        $this->hits = Arr::get($response, 'inner_hits', []);
    }

    public function getParentId($parentType)
    {
        return Arr::get($this->hits, $parentType . '.hits.hits.0._id');
    }

    public function getParent($parentType)
    {
        return Arr::get($this->hits, $parentType . '.hits.hits.0._source');
    }

    public function get()
    {
        return $this->hits;
    }
}

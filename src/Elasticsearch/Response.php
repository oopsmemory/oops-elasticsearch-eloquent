<?php

namespace Isswp101\Persimmon\Elasticsearch;

class Response
{
    public $index;
    public $type;
    public $id;
    public $source = [];

    public function __construct(array $res)
    {
        $this->index = array_get($res, '_index');
        $this->type = array_get($res, '_type');
        $this->id = array_get($res, '_id');
        $this->source = array_get($res, '_source', []);
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getSource()
    {
        return $this->source;
    }
}

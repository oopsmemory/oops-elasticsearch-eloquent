<?php

namespace Isswp101\Persimmon\Elasticsearch;

use Isswp101\Persimmon\Traits\Presentable;

class DocumentPath
{
    use Presentable;

    public $index;
    public $type;
    public $id;

    public function __construct($index, $type, $id)
    {
        $this->index = $index;
        $this->type = $type;
        $this->id = $id;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPath()
    {
        $res = [];
        if (!is_null($this->index)) {
            $res[] = $this->index;
        }
        if (!is_null($this->type)) {
            $res[] = $this->type;
        }
        if (!is_null($this->id)) {
            $res[] = $this->id;
        }
        return implode('/', $res);
    }

    public function isValid()
    {
        return $this->index && $this->type && $this->id;
    }
}
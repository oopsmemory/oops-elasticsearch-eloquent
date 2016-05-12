<?php

namespace Isswp101\Persimmon\Traits;

trait Relationshipable
{
    protected $_parentId;

    public function getParentId()
    {
        return $this->_parentId;
    }

    public function setParentId($id)
    {
        $this->_parentId = $id;
    }
}
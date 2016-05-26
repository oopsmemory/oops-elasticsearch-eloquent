<?php

namespace Isswp101\Persimmon\Traits;

use Isswp101\Persimmon\ElasticsearchModel;

trait Relationshipable
{
    /**
     * @var ElasticsearchModel
     */
    protected $_parent;

    /**
     * @var mixed
     */
    protected $_parentId;

    /**
     * @return mixed
     */
    public function getParentId()
    {
        return $this->_parentId;
    }

    /**
     * @param mixed $id
     */
    public function setParentId($id)
    {
        $this->_parentId = $id;
    }

    /**
     * Return parent document.
     *
     * @return static
     */
    public function getParent()
    {
        return $this->_parent;
    }

    /**
     * Set parent document.
     *
     * @param ElasticsearchModel $parent
     */
    public function setParent(ElasticsearchModel $parent)
    {
        $this->_parent = $parent;
        $this->setParentId($parent->getId());
    }
}
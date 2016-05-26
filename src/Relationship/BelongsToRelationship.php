<?php

namespace Isswp101\Persimmon\Relationship;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Isswp101\Persimmon\ElasticsearchModel;
use ReflectionClass;

class BelongsToRelationship
{
    /**
     * @var ElasticsearchModel
     */
    protected $child;

    /**
     * @var ElasticsearchModel
     */
    protected $parentClassName;

    function __construct(ElasticsearchModel $child, $parentClassName)
    {
        $this->child = $child;
        $this->parentClassName = $parentClassName;
    }

    public function associate($parent)
    {
        $this->child->setParent($parent);
    }

    /**
     * Return parent instance via inner_hits objects.
     *
     * @return ElasticsearchModel
     */
    public function get()
    {
        $parent = $this->child->getParent();

        if (!$parent) {
            $parentClassName = $this->parentClassName;
            $innerHit = $this->child->getInnerHits()->getParent($parentClassName::getType());

            if ($innerHit) {
                $parent = new $parentClassName($innerHit);
            } elseif ($this->child->getParentId()) {
                $parent = $parentClassName::find($this->child->getParentId());
            }

            if (!$parent) {
                $reflect = new ReflectionClass($parentClassName);
                throw new ModelNotFoundException(sprintf(
                    'Model `%s` not found by id `%s`. Try to use inner_hits.',
                    $reflect->getShortName(), $this->child->getParentId()
                ));
            } else {
                $this->child->setParent($parent);
            }
        }

        return $parent;
    }

    /**
     * Return parent instance via inner_hits objects.
     *
     * @return ElasticsearchModel
     */
    public function getOrFail()
    {
        $model = $this->get();

        if (is_null($model)) {
            throw new ModelNotFoundException();
        }

        return $model;
    }
}
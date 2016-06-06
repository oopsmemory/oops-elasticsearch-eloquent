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
    protected $parentClass;

    public function __construct(ElasticsearchModel $child, $parentClass)
    {
        $this->child = $child;
        $this->parentClass = $parentClass;
    }

    public function associate(ElasticsearchModel $parent)
    {
        $this->child->setParent($parent);
    }

    /**
     * Return parent model.
     *
     * @return ElasticsearchModel
     */
    public function get()
    {
        $parent = $this->child->getParent();

        if ($parent) {
            return $parent;
        }

        $parentClass = $this->parentClass;

        $parentId = $this->child->getParentId();

        $innerHits = $this->child->getInnerHits();

        if ($innerHits) {
            $attributes = $innerHits->getParent($parentClass::getType());
            $parent = new $parentClass($attributes);
        } elseif ($parentId) {
            $parent = $parentClass::find($parentId);
        }

        if (!$parent) {
            $reflection = new ReflectionClass($parentClass);
            throw new ModelNotFoundException(sprintf(
                'Model `%s` not found by id `%s`. Try to set parent id in your model or use inner_hits statement.',
                $reflection->getShortName(), $parentId
            ));
        }

        $this->child->setParent($parent);

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
<?php

namespace Isswp101\Persimmon\Traits;

use Isswp101\Persimmon\Model;

trait Mergeable
{
    /**
     * Merge models.
     *
     * @param Model $model1
     * @param Model $model2
     * @param array $attributes
     * @return Model
     */
    public static function merge(Model $model1, Model $model2, array $attributes)
    {
        foreach ($attributes as $attribute) {
            $model1->$attribute = $model2->$attribute;
        }
        return $model1;
    }
}
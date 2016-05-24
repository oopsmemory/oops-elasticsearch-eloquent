<?php

namespace Isswp101\Persimmon\Traits;

use Exception;
use Isswp101\Persimmon\ElasticsearchModel;
use Isswp101\Persimmon\QueryBuilder\QueryBuilder;

trait Paginationable
{
    /**
     * @var ElasticsearchModel
     */
    public $_previous = null;

    /**
     * @var ElasticsearchModel
     */
    public $_next = null;

    /**
     * Search documents and find previous and next documents.
     *
     * @param QueryBuilder $query Query
     * @throws Exception
     */
    public function makePagination(QueryBuilder $query = null)
    {
        if (is_null($this->_position)) {
            throw new Exception('To use Paginationable trait you must fill _position property in your model');
        }

        /** @var ElasticsearchModel $model */
        $model = static::createInstance();

        $query = $query ?: new QueryBuilder();

        $prevDoc = null;
        $nextDoc = null;

        $query->fields();
        $prevPos = $this->_position - 1;

        if ($prevPos >= 0) {
            $items = $model->search($query->from($prevPos)->size(3));
            $prevDoc = $items->first();
            $items = array_values($items->toArray());
            if (array_key_exists(2, $items)) {
                $nextDoc = $items[2];
            }
        } else {
            $items = $model->search($query->from($this->_position)->size(2));
            $total = $items->getTotal();
            $nextDoc = $items->last();

            $last = $total - 1;
            $items = $model->search($query->from($last)->size(1));
            $prevDoc = $items->first();
        }

        if (!$nextDoc) {
            $items = $model->search($query->from(0)->size(1));
            $nextDoc = $items->first();
        }

        $this->_previous = $prevDoc;
        $this->_next = $nextDoc;
    }

    /**
     * Return previous document.
     *
     * @return ElasticsearchModel
     */
    public function getPrevious()
    {
        return $this->_previous;
    }

    /**
     * Return next document.
     *
     * @return ElasticsearchModel
     */
    public function getNext()
    {
        return $this->_next;
    }
}
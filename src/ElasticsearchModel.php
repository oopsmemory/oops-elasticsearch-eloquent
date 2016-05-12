<?php

namespace Isswp101\Persimmon;

use Isswp101\Persimmon\DAL\IDAL;
use Isswp101\Persimmon\Traits\Elasticsearchable;
use Isswp101\Persimmon\Traits\Mappingable;
use Isswp101\Persimmon\Traits\Relationshipable;

class ElasticsearchModel extends Model
{
    use Elasticsearchable, Mappingable, Relationshipable;

    protected $_innerHits = [];

    public function __construct(array $response = [])
    {
        $this->validateEsIndexAndType();

        parent::__construct();

        $this->fillFromResponse($response);
    }

    public function injectDataAccessLayer(IDAL $dal)
    {
        $this->_dal = $dal;
    }

    protected function setInnerHits(array $innerHits)
    {
        $this->_innerHits = $innerHits;
    }

    protected function getInnerHits()
    {
        return $this->_innerHits;
    }

    public static function findWithParentId($id, $parentId, array $columns = ['*'])
    {
        /** @var static $model */
        $model = parent::find($id, $columns, ['parent_id' => $parentId]);

        if ($model) {
            $model->setParentId($parentId);
        }

        return $model;
    }
}
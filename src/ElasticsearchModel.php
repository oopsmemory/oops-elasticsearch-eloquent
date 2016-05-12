<?php

namespace Isswp101\Persimmon;

use Elasticsearch\Client;
use Isswp101\Persimmon\DAL\ElasticsearchDAL;
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

    protected function injectDataAccessLayer()
    {
        $this->_dal = new ElasticsearchDAL($this, $this->getElasticsearchClient());
    }

    protected function getElasticsearchClient()
    {
        return new Client();
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
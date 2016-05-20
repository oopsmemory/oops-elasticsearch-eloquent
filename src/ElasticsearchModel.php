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

    public function __construct(array $response = [])
    {
        $this->validateEsIndexAndType();

        parent::__construct();

        $this->fillFromResponse($response);
    }

    public function injectDependencies()
    {
        $this->injectDataAccessLayer(new ElasticsearchDAL($this, app(Client::class)));
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
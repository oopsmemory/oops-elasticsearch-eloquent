<?php

namespace Isswp101\Persimmon;

use Elasticsearch\Client;
use Isswp101\Persimmon\Collection\ElasticsearchCollection;
use Isswp101\Persimmon\DAL\ElasticsearchDAL;
use Isswp101\Persimmon\Traits\Elasticsearchable;
use Isswp101\Persimmon\Traits\Mappingable;
use Isswp101\Persimmon\Traits\Relationshipable;

class ElasticsearchModel extends Model
{
    use Elasticsearchable, Mappingable, Relationshipable;

    /**
     * @var ElasticsearchDAL
     */
    public $_dal;

    public function __construct(array $attributes = [])
    {
        $this->validateIndexAndType();

        parent::__construct($attributes);
    }

    public function injectDependencies()
    {
        // @TODO: move logger to DAL
        $this->injectDataAccessLayer(new ElasticsearchDAL($this, app(Client::class)));
        // $this->injectLogger(app(Log::class));
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

    /**
     * @param array $query
     * @return ElasticsearchCollection|static[]
     */
    public static function search($query = [])
    {
        $model = static::createInstance();

        return $model->_dal->search($query);
    }
}
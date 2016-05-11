<?php

namespace Isswp101\Persimmon\DAL;

use Elasticsearch\Client;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Isswp101\Persimmon\ElasticsearchModel;
use Isswp101\Persimmon\Model;

class ElasticsearchDAL implements IDAL
{
    protected $model;
    protected $client;

    public function __construct(ElasticsearchModel $model, Client $client)
    {
        $this->model = $model;
        $this->client = $client;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function has($id)
    {
        return true; // @FIXME
    }

    public function get($id, array $options = [])
    {
        $params = $this->model->getFullPath();

        if (array_key_exists('columns', $options) && $options['columns']) {
            $params['_source'] = $options['columns'];
        }

        if (array_key_exists('parent_id', $options) && $options['parent_id']) {
            $params['parent'] = $options['parent_id'];
        }

        $response = $this->client->get($params);

        return $this->model->fillFromResponse($response);
    }

    public function put(Model $instance)
    {
        // TODO: Implement put() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}
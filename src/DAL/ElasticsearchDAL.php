<?php

namespace Isswp101\Persimmon\DAL;

use Elasticsearch\Client;
use Isswp101\Persimmon\ElasticsearchModel;

class ElasticsearchDAL implements IDAL
{
    protected $model;
    protected $client;

    public function __construct(ElasticsearchModel $model, Client $client = null)
    {
        $this->model = $model;
        $this->client = $client;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function get($id, array $options = [])
    {
        $params = $this->model->getPath()->toArray();

        if (!empty($options['columns'])) {
            $params['_source'] = $options['columns'];
        }

        if (!empty($options['parent_id'])) {
            $params['parent'] = $options['parent_id'];
        }

        $response = $this->client->get($params);

        return $this->model->fillFromResponse($response);
    }

    public function put()
    {
        $params = $this->model->getPath()->toArray();

        $params['body'] = $this->model->toArray();

        if ($this->model->getParentId()) {
            $params['parent'] = $this->model->getParentId();
        }

        if (!$params['id']) {
            unset($params['id']);
        }

        $response = $this->client->index($params);

        $this->model->setId($response['_id']);

        return $this->model->getId();
    }

    public function delete()
    {
        $params = $this->model->getPath()->toArray();

        if ($this->model->getParentId()) {
            $params['parent'] = $this->model->getParentId();
        }

        return $this->client->delete($params);
    }
}
<?php

namespace Isswp101\Persimmon\Collection;

use Illuminate\Support\Collection;

class ElasticsearchCollection extends Collection
{
    /**
     * Elasticsearch response.
     *
     * @var array
     */
    protected $response = [];

    /**
     * Put response.
     *
     * @param array $response
     */
    public function response(array $response)
    {
        unset($response['hits']['hits']);
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getTook()
    {
        return $this->response['took'];
    }

    /**
     * @return bool
     */
    public function isTimedOut()
    {
        return $this->response['timed_out'];
    }

    /**
     * @return int
     */
    public function getTotal()
    {
        return $this->response['hits']['total'];
    }

    /**
     * @return int
     */
    public function getMaxScore()
    {
        return $this->response['hits']['max_score'];
    }

    /**
     * @return array
     */
    public function getShards()
    {
        return $this->response['_shards'];
    }

    /**
     * @param string $name
     * @return array
     */
    public function getAggregation($name)
    {
        return array_get($this->response, 'aggregations.' . $name . '.buckets', []);
    }
}
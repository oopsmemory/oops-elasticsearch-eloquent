<?php

namespace Isswp101\Persimmon\Traits;

use Isswp101\Persimmon\Elasticsearch\Response;

trait Elasticsearchable
{
    /**
     * @var string
     */
    protected static $index = null;

    /**
     * @var string
     */
    protected static $type = null;

    /**
     * @return string
     */
    final public static function getIndex()
    {
        return static::$index;
    }

    /**
     * @return string
     */
    final public static function getType()
    {
        return static::$type;
    }

    /**
     * @throws \Exception
     */
    protected function validateEsIndexAndType()
    {
        if (!$this->getIndex()) {
            throw new \Exception('Please specify the index for your Elasticsearch model');
        }

        if (!$this->getType()) {
            throw new \Exception('Please specify the type for your Elasticsearch model');
        }
    }

    /**
     * @param array $response
     * @return $this
     */
    public function fillFromResponse(array $response)
    {
        $res = new Response($response);
        $this->fill($res->getSource());
        $this->setId($res->getId());
        return $this;
    }
}
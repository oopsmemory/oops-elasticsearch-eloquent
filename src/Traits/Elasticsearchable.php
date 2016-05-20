<?php

namespace Isswp101\Persimmon\Traits;

use Exception;
use Isswp101\Persimmon\Elasticsearch\DocumentPath;
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
     * @var array
     */
    public $_innerHits = [];

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
    final protected function validateIndexAndType()
    {
        if (!$this->getIndex()) {
            throw new Exception('Please specify the index for your Elasticsearch model');
        }

        if (!$this->getType()) {
            throw new Exception('Please specify the type for your Elasticsearch model');
        }
    }

    /**
     * @param array $innerHits
     */
    protected function setInnerHits(array $innerHits)
    {
        $this->_innerHits = $innerHits;
    }

    /**
     * @return array
     */
    protected function getInnerHits()
    {
        return $this->_innerHits;
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

    public function getPath()
    {
        return new DocumentPath($this->getIndex(), $this->getType(), $this->getId());
    }
}
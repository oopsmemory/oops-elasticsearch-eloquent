<?php

namespace Isswp101\Persimmon\Traits;

use Exception;
use Isswp101\Persimmon\Elasticsearch\DocumentPath;
use Isswp101\Persimmon\Elasticsearch\InnerHits;
use Isswp101\Persimmon\Elasticsearch\Response;

trait Elasticsearchable
{
    /**
     * @var string
     */
    protected static $index;

    /**
     * @var string
     */
    protected static $type;

    /**
     * @var string
     */
    protected static $parentType;

    /**
     * @var InnerHits
     */
    public $_innerHits;

    /**
     * @var float
     */
    public $_score;

    /**
     * @var int
     */
    public $_position;

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
     * @return string
     */
    final public static function getParentType()
    {
        return static::$parentType;
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
     * @param InnerHits $innerHits
     */
    protected function setInnerHits(InnerHits $innerHits)
    {
        $this->_innerHits = $innerHits;
    }

    /**
     * @return InnerHits
     */
    protected function getInnerHits()
    {
        return $this->_innerHits;
    }

    /**
     * @param array $response
     * @return $this
     */
    public function fillByResponse(array $response)
    {
        $res = new Response($response);
        $this->fill($res->getSource());
        $this->setId($res->getId());
        return $this;
    }

    /**
     * @param array $response
     * @return $this
     */
    public function fillByInnerHits(array $response)
    {
        $innerHits = new InnerHits($response);
        $this->setInnerHits($innerHits);
        $this->setParentId($innerHits->getParentId($this->getParentType()));
        return $this;
    }

    public function getPath()
    {
        return new DocumentPath($this->getIndex(), $this->getType(), $this->getId());
    }
}
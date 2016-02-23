<?php

namespace Isswp101\Persimmon\Traits;

trait Elasticsearchable
{
    /**
     * @var string
     */
    protected static $ES_INDEX = null;

    /**
     * @var string
     */
    protected static $ES_TYPE = null;

    /**
     * @return string
     */
    public function getEsIndex()
    {
        return static::$ES_INDEX;
    }

    /**
     * @return string
     */
    public function getEsType()
    {
        return static::$ES_TYPE;
    }
}
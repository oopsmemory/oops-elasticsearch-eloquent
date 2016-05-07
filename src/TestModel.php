<?php

namespace Isswp101\Persimmon;

use Isswp101\Persimmon\Elasticsearch\Response;

class TestModel extends Model
{
    public static $index = 'index';

    public static $type = 'type';

    public static function getIndex()
    {
        return static::$index;
    }

    public static function getType()
    {
        return static::$type;
    }

    public function __construct(array $response = [])
    {
        parent::__construct();

        $this->fillFromResponse($response);
    }

    public function fillFromResponse(array $response)
    {
        $res = new Response($response);
        $this->fill($res->getSource());
        $this->setId($res->getId());
        return $this;
    }

    public function save()
    {

    }
}
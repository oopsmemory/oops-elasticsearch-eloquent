<?php

namespace Isswp101\Persimmon\Test\Models;

use Elasticsearch\Client;
use Isswp101\Persimmon\DAL\ElasticsearchDAL;
use Isswp101\Persimmon\ElasticsearchModel as Model;
use Psr\Log\LoggerInterface;

class ElasticsearchModel extends Model
{
    public function __construct(array $attributes = [])
    {
        $dal = new ElasticsearchDAL($this, app(Client::class), app(LoggerInterface::class));

        parent::__construct($dal, $attributes);
    }
}
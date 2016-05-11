<?php

namespace Isswp101\Persimmon;

use Isswp101\Persimmon\Traits\Elasticsearchable;
use Isswp101\Persimmon\Traits\Mappingable;

class ElasticsearchModel extends Model
{
    use Elasticsearchable, Mappingable;

    public function __construct(array $response = [])
    {
        $this->validateEsIndexAndType();

        parent::__construct();

        $this->fillFromResponse($response);
    }

    public function save()
    {

    }
}
<?php

namespace Isswp101\Persimmon;

use Isswp101\Persimmon\Traits\Elasticsearchable;

class ElasticsearchModel extends Model
{
    use Elasticsearchable;

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
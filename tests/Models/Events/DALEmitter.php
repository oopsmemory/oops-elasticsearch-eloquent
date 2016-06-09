<?php

namespace Isswp101\Persimmon\Test\Models\Events;

use Isswp101\Persimmon\DAL\DALMediator;

class DALEmitter extends DALMediator
{
    public function __construct()
    {
        $this->attach(DALMediator::EVENT_BEFORE_SEARCH, function (array $params) {
            // Logging elasticsearch queries
            return $params;
        });
    }
}

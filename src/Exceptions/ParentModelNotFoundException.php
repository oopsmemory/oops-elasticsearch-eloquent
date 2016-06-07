<?php

namespace Isswp101\Persimmon\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException as Exception;
use ReflectionClass;

class ParentModelNotFoundException extends Exception
{
    public function __construct($class, $id)
    {
        $reflection = new ReflectionClass($class);

        $model = $reflection->getShortName();

        $message = sprintf(
            'Model `%s` not found by id `%s`. Try to set parent id in your model or use inner_hits feature.',
            $model, $id
        );

        parent::__construct($message);
    }
}
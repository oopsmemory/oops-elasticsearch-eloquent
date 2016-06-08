<?php

namespace Isswp101\Persimmon\Test\Models;

use Isswp101\Persimmon\ElasticsearchModel;
use Exception;

class EventableModel extends ElasticsearchModel
{
    public static $index = 'test_events';
    public static $type = 'events';

    public $name;
    public $price = 0;

    protected function saving()
    {
        $this->price = 100500;
    }

    protected function saved()
    {
        $this->price = 1050;
    }

    protected function updating()
    {
        return true;
    }

    protected function updated()
    {
        return true;
    }

    protected function creating()
    {
        return true;
    }

    protected function created()
    {
        return true;

    }

    protected function deleting()
    {
        if ($this->price !== 100)
        {
            throw new Exception();
        }
    }

    protected function deleted()
    {
        throw new Exception();
    }

}
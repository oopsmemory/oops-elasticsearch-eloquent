<?php

namespace Isswp101\Persimmon\Test\Models;

use Isswp101\Persimmon\ElasticsearchModel;

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

    protected function deleting()
    {
        if ($this->price !== 100) {
            return false;
        }

        return true;
    }

    protected function deleted()
    {
        $this->price = 1;
    }
}

<?php

namespace Isswp101\Persimmon\Test\Models;

use Isswp101\Persimmon\ElasticsearchModel;

class Product extends ElasticsearchModel
{
    public static $index = 'test';
    public static $type = 'test';

    public $name;
    public $price = 0;
}

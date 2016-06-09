<?php

namespace Isswp101\Persimmon\Test\Models;

class Product extends ElasticsearchModel
{
    public static $index = 'test';
    public static $type = 'test';

    public $name;
    public $price = 0;
}

<?php

namespace Isswp101\Persimmon;

class Product extends ElasticsearchModel
{
    protected static $index = 'test';
    protected static $type = 'test';

    public $name;
    public $price = 0;
}
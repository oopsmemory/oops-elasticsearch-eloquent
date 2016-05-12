<?php

namespace Isswp101\Persimmon;

class Product extends ElasticsearchModel
{
    protected static $index = 'products';
    protected static $type = 'product';

    public $name;
    public $price = 0;
}
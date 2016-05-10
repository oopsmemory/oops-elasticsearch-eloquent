<?php

namespace Isswp101\Persimmon;

class TestModel extends ElasticsearchModel
{
    protected static $index = 'base';

    protected static $type = 'items';
}
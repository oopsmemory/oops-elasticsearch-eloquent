<?php

namespace Isswp101\Persimmon\Test\Models;

use Isswp101\Persimmon\ElasticsearchModel;

class PurchaseOrder extends ElasticsearchModel
{
    public static $index = 'test_parent_child_rel';
    public static $type = 'orders';

    public $name;

    public function lines()
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }
}

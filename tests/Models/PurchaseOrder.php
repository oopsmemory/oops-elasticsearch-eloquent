<?php

namespace Isswp101\Persimmon\Test\Models;

use Isswp101\Persimmon\ElasticsearchModel;

class PurchaseOrder extends ElasticsearchModel
{
    protected static $index = 'test_parent_child_rel';
    protected static $type = 'orders';

    public $name;

    public function lines()
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }
}

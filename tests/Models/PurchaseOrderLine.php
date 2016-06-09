<?php

namespace Isswp101\Persimmon\Test\Models;

class PurchaseOrderLine extends ElasticsearchModel
{
    public static $index = 'test_parent_child_rel';
    public static $type = 'lines';
    public static $parentType = 'orders';

    public $name;

    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}

<?php

namespace Isswp101\Persimmon\Test\Models;

use Isswp101\Persimmon\ElasticsearchModel;

class PurchaseOrderLine extends ElasticsearchModel
{
    protected static $index = 'test_parent_child_rel';
    protected static $type = 'lines';

    public $name;

    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
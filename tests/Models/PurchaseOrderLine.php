<?php

namespace Isswp101\Persimmon\Test\Models;

use Isswp101\Persimmon\ElasticsearchModel;
use Isswp101\Persimmon\Test\Models\PurchaseOrder;

class PurchaseOrderLine extends ElasticsearchModel
{
    protected static $index = 'lines';
    protected static $type = 'lines';

    public $name;
    public $parent;
    
    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
<?php

namespace Isswp101\Persimmon\Test\Models;

use Isswp101\Persimmon\ElasticsearchModel;
use Isswp101\Persimmon\Test\Models\PurchaseOrder;

class PurchaseOrderLine extends ElasticsearchModel
{
    protected static $index = 'testrelationshippoline';
    protected static $type = 'testrelationshippoline';

    public $name;

    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
<?php

namespace Isswp101\Persimmon\Test\Models;

use Isswp101\Persimmon\ElasticsearchModel;
use Isswp101\Persimmon\Test\Models\PurchaseOrder;

class PurchaseOrderLine extends ElasticsearchModel
{
    protected static $index = 'testRelationshipPOLine';
    protected static $type = 'testRelationshipPOLine';

    public $name;

    public function po()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
}
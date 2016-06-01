<?php

namespace Isswp101\Persimmon\Test\Models;

use Isswp101\Persimmon\ElasticsearchModel;
use Isswp101\Persimmon\Test\Models\PurchaseOrderLine;

class PurchaseOrder extends ElasticsearchModel
{
    protected static $index = 'orders';
    protected static $type = 'orders';

    public $name;

    public function line()
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }
}
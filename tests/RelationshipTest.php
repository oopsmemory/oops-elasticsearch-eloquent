<?php

namespace Isswp101\Persimmon\Test;

use Isswp101\Persimmon\Test\Models\PurchaseOrder;
use Isswp101\Persimmon\Test\Models\PurchaseOrderLine;

class RelationshipTest extends BaseTestCase
{
    public function testPrepareIndex()
    {
        $this->assertTrue(true);
    }

    public function testSaveHasMany()
    {
        $po = new PurchaseOrder();

        $line = new PurchaseOrderLine();
        $line->name = 'Line1';

        $poLine = $po->line();

        //$this->assertNull($poLine);

        $po->line()->save($line);

        $poLine = $po->line();

        $this->assertEquals(1, $this->count($poLine));
        $this->assertEquals('Line1', $poLine[0]);
        $this->assertInstanceOf(PurchaseOrderLine::class, $poLine[0]);
    }

    public function testAssociateBelongsTo()
    {
        $po = new PurchaseOrder();
        $po->name = 'PO1';

        $line = new PurchaseOrderLine();
        $line->name = 'Line1';

        $line->po()->associate($po);

        $this->assertInstanceOf(PurchaseOrder::class, $line->po()[0]);
    }
}

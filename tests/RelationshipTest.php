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
        $po->id = 1;
        $line = new PurchaseOrderLine();

        $res1 = $this->es->get($po->getPath()->toArray());

        $po->line()->save($line);

        $res2 = $this->es->get($po->getPath()->toArray());

        $this->assertNotEquals($res1, $res2);
    }

    public function testGetHasMany()
    {
        $po = new PurchaseOrder();
        $line = new PurchaseOrderLine();

        $line->id = 5;
        $line->name = 'Line1';

        $po->line()->save($line);

        $poLine = $po->line()->get();

        $this->assertEquals(1, $this->count($poLine));
        $this->assertEquals(5, $poLine[0]->id);
        $this->assertEquals('Line1', $poLine);
        $this->assertInstanceOf(PurchaseOrderLine::class, $poLine[0]);
    }

    public function testAssociateBelongsTo()
    {
        $po = new PurchaseOrder();

        $line = new PurchaseOrderLine();

        $line->po()->associate($po);

        $this->assertInstanceOf(PurchaseOrder::class, $line->po()->get());
    }

    public function testFindHasMany()
    {
        $po = new PurchaseOrder();
        $line = new PurchaseOrderLine();

        $line->id = 6;
        $line->name = 'Line6';

        $po->line()->save($line);

        $poLine = $po->line()->find(6);

        $this->assertEquals(6, $poLine->id);
        $this->assertEquals('Line6', $poLine->name);
        $this->assertInstanceOf(PurchaseOrderLine::class, $poLine);
    }
}

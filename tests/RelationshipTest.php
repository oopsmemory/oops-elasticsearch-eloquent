<?php

namespace Isswp101\Persimmon\Test;

use Isswp101\Persimmon\Test\Models\PurchaseOrder;
use Isswp101\Persimmon\Test\Models\PurchaseOrderLine;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RelationshipTest extends BaseTestCase
{
    public function testPrepareIndex()
    {
        //$this->assertTrue(true);

        $index = PurchaseOrderLine::getIndex();
        $mapping = file_get_contents(__DIR__.'../../elastic/purchase-order.json');

        try {
            $this->es->indices()->delete(['index' => $index]);
        } catch (Missing404Exception $e) {
        }

        sleep(2);

        $this->es->indices()->create(['index' => $index, 'body' => $mapping]);
    }

    public function testSaveHasManyRelationship()
    {
        $po = new PurchaseOrder();
        $line = new PurchaseOrderLine();
        $line->id = 1;

        $res1 = $this->es->get($line->getPath()->toArray());

        $po->line()->save($line);

        $res2 = $this->es->get($line->getPath()->toArray());

        $this->assertNotEquals($res1, $res2);
    }

    public function testGetHasManyRelationship()
    {
        $po = new PurchaseOrder();
        $po->id = 2;
        $po->save();
        $line = new PurchaseOrderLine();

        $line->id = 5;
        $line->name = 'Line5';

        $po->line()->save($line);

        $poLine = $po->line()->get();

        $this->assertEquals(1, $this->count($poLine));
        $this->assertEquals(5, $poLine[0]->id);
        $this->assertEquals('Line1', $poLine);
        $this->assertInstanceOf(PurchaseOrderLine::class, $poLine[0]);
    }

    public function testFindHasManyRelationship()
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

    public function testAssociateBelongsToRelationship()
    {
        $po = new PurchaseOrder();

        $line = new PurchaseOrderLine();

        $line->po()->associate($po);

        $this->assertInstanceOf(PurchaseOrder::class, $line->po()->get());
    }

    public function testGetBelongsToRelationship()
    {
        $line = new PurchaseOrderLine();
        $po1 = new PurchaseOrder();
        $po1->name = 'PO3';
        $po1->id = 3;

        $line->po()->associate($po1);
        $po2 = $line->po()->get();

        $this->assertEquals($po1, $po2);
    }

    public function testGetOrFailBelongsToRelationship()
    {
        $this->expectException(ModelNotFoundException::class);
        PurchaseOrderLine::findOrFail(99);
    }
}

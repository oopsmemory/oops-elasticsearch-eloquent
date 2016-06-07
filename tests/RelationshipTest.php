<?php

namespace Isswp101\Persimmon\Test;

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Isswp101\Persimmon\Test\Models\PurchaseOrder;
use Isswp101\Persimmon\Test\Models\PurchaseOrderLine;

class RelationshipTest extends BaseTestCase
{
    public function testPrepareIndex()
    {
        $index = PurchaseOrderLine::getIndex();

        try {
            $this->es->indices()->delete(['index' => $index]);
        } catch (Missing404Exception $e) {
        }

        sleep(2);

        $settings = file_get_contents(__DIR__ . '/index.json');
        $this->es->indices()->create(['index' => $index, 'body' => $settings]);
    }

    public function testSaveWithBadRequest400Exception()
    {
        $po = new PurchaseOrder();
        $po->id = 1;
        $po->name = 'PO1';

        $line = new PurchaseOrderLine();
        $line->id = 1;
        $line->name = 'Line1';

        $this->expectException(BadRequest400Exception::class);

        $po->save();
        $line->save();
    }

    public function testSave()
    {
        $po = new PurchaseOrder();
        $po->id = 1;
        $po->name = 'PO1';

        $line = new PurchaseOrderLine();
        $line->id = 1;
        $line->name = 'Line1';

        $po->save();
        $po->lines()->save($line);

        $poPath = $po->getPath()->toArray();
        $linePath = $line->getPath()->toArray();

        $res = $this->es->get($poPath);
        $this->assertEquals(1, $res['_id']);
        $this->assertEquals('PO1', $res['_source']['name']);

        $res = $this->es->get($linePath);
        $this->assertEquals(1, $res['_id']);
        $this->assertEquals('Line1', $res['_source']['name']);
    }

    public function testAssociate()
    {
        $po = new PurchaseOrder();
        $po->id = 1;
        $po->name = 'PO1';

        $line = new PurchaseOrderLine();
        $line->id = 1;
        $line->name = 'Line1';

        $po->save();
        $line->po()->associate($po);
        $line->save();

        $poPath = $po->getPath()->toArray();
        $linePath = $line->getPath()->toArray();

        $res = $this->es->get($poPath);
        $this->assertEquals(1, $res['_id']);
        $this->assertEquals('PO1', $res['_source']['name']);

        $res = $this->es->get($linePath);
        $this->assertEquals(1, $res['_id']);
        $this->assertEquals('Line1', $res['_source']['name']);
    }

    public function testGetParent()
    {
        $line = PurchaseOrderLine::findWithParentId(1, 1);
        $po = $line->po()->getOrFail();
        $this->assertEquals(1, $line->getParentId());
        $this->assertEquals(1, $po->getId());
    }

    public function testGetChildren()
    {
        $po = PurchaseOrder::findOrFail(1);

        $line = $po->lines()->find(1);
        $this->assertEquals(1, $line->getId());
        $this->assertEquals(1, $line->getParentId());
        $this->assertSame($po, $line->getParent());

        $lines = $po->lines()->get();
        $this->assertEquals(1, $lines->count());
        $this->assertEquals($line->toArray(), $lines->first()->toArray());
        $this->assertEquals($line->getParent()->toArray(), $lines->first()->getParent()->toArray());
    }
}

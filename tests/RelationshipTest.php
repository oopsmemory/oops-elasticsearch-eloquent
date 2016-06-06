<?php

namespace Isswp101\Persimmon\Test;

use Elasticsearch\Common\Exceptions\BadRequest400Exception;
use Elasticsearch\Common\Exceptions\Missing404Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

    /** @group fail */
    public function testGetParent()
    {
        $line = PurchaseOrderLine::findWithParentId(1, 1);
        $po = $line->po()->getOrFail();
        $this->assertEquals(1, $line->getParentId());
        $this->assertEquals(1, $po->getId());
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

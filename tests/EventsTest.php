<?php

namespace Isswp101\Persimmon\Test;

use Isswp101\Persimmon\Test\Models\EventableModel;
use Exception;

class EventsTest extends BaseTestCase
{
    public static function setUpBeforeClass()
    {
        $hash = time();
        EventableModel::$index = 'travis_ci_test_events' . $hash;
    }

    public function testSavingAndSaved()
    {
        $model = new EventableModel();
        $model->save();

        $path = $model->getPath()->toArray();
        $res = $this->es->get($path);

        $this->assertEquals(100500, $res['_source']['price']);  // before saving price set to 100500
        $this->assertEquals(1050, $model->price);   // after saving price set to 1050

        return $model;
    }

    /**
     * @depends testSavingAndSaved
     */
    public function testDeletingAndDelete(EventableModel $model)
    {
        $this->expectException(Exception::class);   // before saving if price !== 100 throws Exception
        $model->delete();

        $model->price = 100;

        $this->expectException(Exception::class);   // after deleting throws Exception
        $model->delete();
    }

    public function testTearDown()
    {
        $this->deleteIndex(EventableModel::$index);
    }

}
<?php

namespace Isswp101\Persimmon\Test;

use Isswp101\Persimmon\TestModel;
use Isswp101\Persimmon\Model;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    public function test1()
    {
        $model = new TestModel(['_source' => ['id' => 1]]);

        dd($model->toJson());
        $this->assertInstanceOf(Model::class, $model);
    }
}

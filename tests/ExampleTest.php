<?php

namespace Isswp101\Persimmon\Test;

use Isswp101\Persimmon\Model;

class ExampleTest extends \PHPUnit_Framework_TestCase
{
    public function test1()
    {
        $model = new Model();
        $this->assertInstanceOf(Model::class, $model);
    }
}

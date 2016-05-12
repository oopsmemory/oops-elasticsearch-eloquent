<?php

namespace Isswp101\Persimmon\Test;

use Elasticsearch\Client;
use Isswp101\Persimmon\DAL\ElasticsearchDAL;
use Isswp101\Persimmon\Product;
use Isswp101\Persimmon\Model;

class BasicFeaturesTest extends \PHPUnit_Framework_TestCase
{
    public function test1()
    {
        $product = new Product();
        $product->injectDataAccessLayer(new ElasticsearchDAL($product/*, new Client()*/));

        $product->name = 'Product 1';
        $product->price = 20;
        // $product->save();

        $this->assertInstanceOf(Model::class, $product);
    }
}

<?php

namespace Isswp101\Persimmon\Test;

use Dotenv\Dotenv;
use Elasticsearch\Client;
use Isswp101\Persimmon\DAL\ElasticsearchDAL;
use Isswp101\Persimmon\Model;
use Isswp101\Persimmon\Product;
use Monolog\Logger;

class BasicFeaturesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Product
     */
    protected $product;

    protected function setUp()
    {
        // Env
        $dotenv = new Dotenv(__DIR__);
        $dotenv->load();

        // Elasticsearch client
        $params = [
            'hosts' => [
                env('ELASTICSEARCH_HOSTS', '')
            ],
            'logPath' => 'app/storage/logs',
            'logLevel' => Logger::INFO,
            'connectionParams' => [
                'auth' => [
                    env('ELASTICSEARCH_AUTH_USER', ''),
                    env('ELASTICSEARCH_AUTH_PASS', ''),
                    'Basic'
                ]
            ]
        ];

        $this->client = new Client($params);

        // Model
        $product = new Product();
        $product->injectDataAccessLayer(new ElasticsearchDAL($product, $this->client));
    }

    public function testSave()
    {
        $this->product->id = 1;
        $this->product->name = 'Product 1';
        $this->product->price = 20;
        $this->product->save();
        $this->assertInstanceOf(Model::class, $this->product);
    }

    public function testFind()
    {
        $this->product = Product::find(1);
        $this->assertEquals('Product 1', $this->product->name);
        $this->assertEquals('20', $this->product->price);
        $this->assertEquals(1, $this->product->getId());
        $this->assertInstanceOf(Model::class, $this->product);
    }
}

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
    }

    public function testCreateNewProduct()
    {
        $product = new Product();
        $product->injectDataAccessLayer(new ElasticsearchDAL($product, $this->client));

        $product->id = 1;
        $product->name = 'Product 1';
        $product->price = 20;
        $product->save();

        $this->assertInstanceOf(Model::class, $product);
    }
}

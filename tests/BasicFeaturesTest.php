<?php

namespace Isswp101\Persimmon\Test;

use Carbon\Carbon;
use Dotenv\Dotenv;
use Elasticsearch\Client;
use Isswp101\Persimmon\ElasticsearchModel;
use Isswp101\Persimmon\Model;
use Isswp101\Persimmon\Product;
use Monolog\Logger;
use Orchestra\Testbench\TestCase;

class BasicFeaturesTest extends TestCase
{
    /**
     * @var Client
     */
    protected $es;

    /**
     * @var Product
     */
    protected $product;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadDotenv();

        $this->es = app(Client::class);

        $this->product = new Product();
    }

    /**
     * Load Dotenv.
     */
    protected function loadDotenv()
    {
        $dotenv = new Dotenv(__DIR__);
        $dotenv->load();
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app->singleton(Client::class, function () {
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
            return new Client($params);
        });
    }

    public function testSave()
    {
        $this->product->id = 1;
        $this->product->name = 'Product 1';
        $this->product->price = 20;
        $this->product->save();

        $this->assertInstanceOf(Model::class, $this->product);
        $this->assertInstanceOf(ElasticsearchModel::class, $this->product);

        $res = $this->es->get($this->product->getPath()->toArray());

        $this->assertEquals($this->product->getIndex(), $res['_index']);
        $this->assertEquals($this->product->getType(), $res['_type']);
        $this->assertEquals($this->product->getId(), $res['_id']);

        $this->assertEquals(1, $res['_id']);
        $this->assertEquals('Product 1', $res['_source']['name']);
        $this->assertEquals(20, $res['_source']['price']);

        $this->assertNotNull($res['_source']['created_at']);
        $this->assertNotNull($res['_source']['updated_at']);
        $this->assertInstanceOf(Carbon::class, $this->product->getCreatedAt());
        $this->assertInstanceOf(Carbon::class, $this->product->getUpdatedAt());
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

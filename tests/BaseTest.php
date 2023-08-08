<?php

namespace Isswp101\Persimmon\Tests;

use Elastic\Elasticsearch\ClientBuilder;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Isswp101\Persimmon\Exceptions\ModelNotFoundException;
use Isswp101\Persimmon\Tests\Models\Product;
use PHPUnit\Framework\TestCase;

function dd(mixed $value): void
{
    print_r($value);
    echo PHP_EOL;
    exit();
}

class BaseTest extends TestCase
{
    private Client $client;

    private array $attributes = [
        'name' => 'Name',
        'price' => 10
    ];

    protected function setUp(): void
    {

        $this->client = ClientBuilder::create()
            // ->setSSLVerification(true)
            // ->setHosts(["localhost:9200"])
            ->setHosts(['https://localhost:9200'])
            ->setBasicAuthentication('elastic', 'VVL8HFS6Uo8s3dEo0YX+')
            // ->setCABundle('./elasticdev8/http_ca.crt')
            ->build();
    }

    private function sleep(int $seconds = 1): void
    {
        sleep($seconds);
    }

    public function testFillModel(): void
    {
        $a = new Product($this->attributes);

        $b = new Product();
        $b->fill($this->attributes);

        $this->assertEquals($this->attributes, $a->toArray());
        $this->assertEquals($this->attributes, $b->toArray());
    }

    public function testCreateModel(): void
    {
        $product = Product::create($this->attributes);

        $this->sleep();

        $this->assertNotNull($product->getId());

        $params = [
            'index' => $product->getIndex(),
            'type' => $product->getType(),
            'id' => $product->getId()
        ];

        $res = $this->client->get($params)['_source'];

        $this->assertEquals($product->name, $res['name']);
        $this->assertEquals($product->price, $res['price']);
    }

    public function testDeleteModel(): void
    {
        $this->expectException(ClientResponseException::class);
);


        $this->sleep();

        $this->assertTrue($product->exists());

        $product->delete();

        $this->assertFalse($product->exists());

        Product::destroy('1');
    }

    public function testFindModel(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $product = Product::create($this->attributes);

        $this->sleep();

        $found = Product::find($product->getId());
        $this->assertNotNull($found);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($found->toArray(), $product->toArray());

        $found = Product::find($product->getId(), ['name']);
        $this->assertEquals($found->name, $product->name);
        $this->assertNotEquals($found->toArray(), $product->toArray());

        Product::findOrFail(1000);
    }

    public function testFirstModel(): void
    {
        Product::create($this->attributes);

        $this->sleep();

        $query = [
            'query' => [
                'match' => [
                    'name' => $this->attributes['name']
                ]
            ]
        ];

        $products = Product::search($query);
        $this->assertGreaterThanOrEqual(1, count($products));

        $product = Product::first($query);
        $this->assertNotNull($product);
        $this->assertInstanceOf(Product::class, $product);
    }

    public function testFirstOrFailModel(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $query = [
            'query' => [
                'match' => [
                    'name' => $this->attributes['price']
                ]
            ]
        ];

        Product::firstOrFail($query);
    }

    public function testAllModels(): void
    {
        for ($i = 0; $i < 10; $i++) {
            Product::create($this->attributes);

            $this->sleep();
        }

        $products = Product::all();

        $this->assertGreaterThanOrEqual(10, count($products));
    }

    public function testUpdateModel(): void
    {
        $product = new Product($this->attributes);

        $product->save();

        $this->assertNotNull($product->id);
        $this->assertNotNull($product->created_at);
        $this->assertNotNull($product->updated_at);
        $this->assertEquals($this->attributes['name'], $product->name);

        sleep(1); // created_at must not equal updated_at

        $product->name = 'Updated';

        $product->save(['name']);

        $this->sleep();

        $params = [
            'index' => $product->getIndex(),
            'type' => $product->getType(),
            'id' => $product->getId()
        ];

        $res = $this->client->get($params)['_source'];
        $this->assertEquals('Updated', $res['name']);
        $this->assertNotEquals($res['created_at'], $res['updated_at']);
    }
}

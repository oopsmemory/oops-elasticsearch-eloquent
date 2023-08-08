<?php

namespace Isswp101\Persimmon\Models;

use Elastic\Elasticsearch\ClientBuilder;
use Isswp101\Persimmon\Concerns\Attributable;
use Isswp101\Persimmon\Concerns\Elasticsearchable;
use Isswp101\Persimmon\Concerns\Eventable;
use Isswp101\Persimmon\Concerns\Existable;
use Isswp101\Persimmon\Concerns\Timestampable;
use Isswp101\Persimmon\Contracts\Arrayable;
use Isswp101\Persimmon\Contracts\ElasticsearchModelContract;
use Isswp101\Persimmon\Contracts\Persistencable;
use Isswp101\Persimmon\Contracts\PersistenceContract;
use Isswp101\Persimmon\DTO\Id;
use Isswp101\Persimmon\DTO\Path;
use Isswp101\Persimmon\Exceptions\ModelNotFoundException;
use Isswp101\Persimmon\Persistence\Persistence;
use Stringable;

/**
 * @property int|string|null id
 * @property string created_at
 * @property string updated_at
 */
abstract class BaseElasticsearchModel implements ElasticsearchModelContract, Persistencable, Arrayable, Stringable
{
    use Elasticsearchable, Timestampable, Eventable, Existable, Attributable;

    private PersistenceContract $persistence;

    protected int $perRequest = 50;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);

        $this->persistence = $this->createPersistence();
    }

    public function createPersistence(): PersistenceContract
    {
        $client = ClientBuilder::create()
            ->setHosts(['https://localhost:9200'])
            // ->setSSLVerification()
            // ->setHttpClient(new HttpClient(['verify' => false ]))
            ->setBasicAuthentication('elastic', 'VVL8HFS6Uo8s3dEo0YX+')
            ->build();

        return new Persistence($client);
    }

    public function getId(): int|string|null
    {
        return $this->id;
    }

    private function persist(Path $path, array $keys): Id
    {
        if ($this->exists && $keys) {
            $keys = $this->timestamps ? array_merge($keys, ['created_at', 'updated_at']) : $keys;
            return $this->persistence->update($path, $this->toArray($keys));
        }
        return $this->persistence->create($path, $this->toArray($keys));
    }

    public function save(array $columns = []): void
    {
        if (!$this->saving()) {
            return;
        }

        $path = new Path($this->index, $this->type, new Id($this->id));

        $this->touch($this->exists);

        $this->id = $this->persist($path, $columns)->value();

        $this->exists = true;

        $this->saved();
    }

    public function delete(): void
    {
        if (!$this->deleting()) {
            return;
        }

        $path = new Path($this->index, $this->type, new Id($this->id));

        $this->persistence->delete($path);

        $this->exists = false;

        $this->deleted();
    }

    public static function create(array $attributes): static
    {
        $model = new static($attributes);

        $model->save();

        return $model;
    }

    public static function find(int|string $id, array $columns = []): static|null
    {
        $model = new static();

        $path = new Path($model->getIndex(), $model->getType(), new Id($id));

        $attributes = $model->persistence->find($path, $columns);

        if (!$attributes) {
            return null;
        }

        $model->fill($attributes);

        $model->id = $id;

        $model->exists = true;

        return $model;
    }

    public static function findOrFail(int|string $id, array $columns = []): static
    {
        return static::find($id, $columns) ?? throw new ModelNotFoundException();
    }

    public static function destroy(int|string $id): void
    {
        $model = new static();

        $model->id = $id;

        $model->delete();
    }

    public static function search(array $query = []): array
    {
        $model = new static();

        if (!$model->searching()) {
            return [];
        }

        $path = new Path($model->index, $model->type, Id::undefined());

        $response = $model->persistence->search($path, $query);

        $models = [];
        foreach ($response->getItems() as $attributes) {
            $models[] = new static($attributes);
        }

        $model->searched($response);

        return $models;
    }

    public static function first(array $query): static|null
    {
        $query['size'] = 1;

        $items = static::search($query);

        return $items[0] ?? null;
    }

    public static function firstOrFail(array $query): static
    {
        return static::first($query) ?? throw new ModelNotFoundException();
    }

    public static function all(array $query = []): array
    {
        $items = [];

        $model = new static();

        $query['from'] = 0;
        $query['size'] = $model->perRequest;
        $itemsPerRequest = static::search($query);
        $items = array_merge($items, $itemsPerRequest);

        while (count($itemsPerRequest) == $model->perRequest) {
            $query['from'] += $model->perRequest;
            $itemsPerRequest = static::search($query);
            $items = array_merge($items, $itemsPerRequest);
        }

        return $items;
    }
}

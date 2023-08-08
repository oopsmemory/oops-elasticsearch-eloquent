<?php

namespace Isswp101\Persimmon\DTO;

use Elastic\Elasticsearch\Response\Elasticsearch;

final class SearchResponse
{
    private int $total;
    private array $items = [];

    public function __construct(Elasticsearch $response)
    {
        $response = $response->asArray();

        $this->total = $response['hits']['total']['value'];

        foreach ($response['hits']['hits'] as $hit) {
            $this->items[] = $hit['_source'];
        }
    }

    public function getTotal(): int
    {
        return $this->total;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}

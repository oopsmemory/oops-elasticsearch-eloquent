<?php

namespace Isswp101\Persimmon\Contracts;

use Isswp101\Persimmon\Exceptions\ModelNotFoundException;

interface Persistencable
{
    public function createPersistence(): PersistenceContract;

    public function save(array $columns): void;

    public function delete(): void;

    public static function create(array $attributes): static;

    public static function find(int|string $id, array $columns = []): static|null;

    /**
     * @param int|string $id
     * @param array $columns
     * @return static
     * @throws ModelNotFoundException
     */
    public static function findOrFail(int|string $id, array $columns = []): static;

    public static function destroy(int|string $id): void;

    public static function search(array $query): array;

    public static function first(array $query): static|null;

    /**
     * @param array $query
     * @return static
     * @throws ModelNotFoundException
     */
    public static function firstOrFail(array $query): static;

    public static function all(array $query): array;
}

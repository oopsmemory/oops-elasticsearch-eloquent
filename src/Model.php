<?php

namespace Isswp101\Persimmon;

use Elasticsearch\Common\Exceptions\Missing404Exception;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Isswp101\Persimmon\Contracts\Arrayable;
use Isswp101\Persimmon\Contracts\Jsonable;
use Isswp101\Persimmon\Contracts\Stringable;
use Isswp101\Persimmon\DAL\IDAL;
use Isswp101\Persimmon\Traits\Cacheable;
use Isswp101\Persimmon\Traits\Eventable;
use Isswp101\Persimmon\Traits\Fillable;
use Isswp101\Persimmon\Traits\Idable;
use Isswp101\Persimmon\Traits\Logable;
use Isswp101\Persimmon\Traits\Mergeable;
use Isswp101\Persimmon\Traits\Presentable;
use Isswp101\Persimmon\Traits\Timestampable;
use Isswp101\Persimmon\Traits\Userable;
use JsonSerializable;
use ReflectionClass;

abstract class Model implements Arrayable, Jsonable, Stringable, JsonSerializable
{
    use Idable, Userable, Timestampable;
    use Fillable, Cacheable, Logable;
    use Presentable, Eventable, Mergeable;

    /**
     * @var IDAL
     */
    public $_dal;

    /**
     * @var bool
     */
    public $_exist = false;

    /**
     * Create a new instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->injectDependencies();

        $this->fill($attributes);
    }

    /**
     * Inject data access layer.
     *
     * @param IDAL $dal
     */
    protected function injectDataAccessLayer(IDAL $dal)
    {
        $this->_dal = $dal;
    }

    abstract public function injectDependencies();

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_where(get_object_vars($this), function ($key) {
            return !starts_with($key, '_');
        });
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Save the model.
     *
     * @param array $columns
     * @return bool
     */
    public function save($columns = ['*'])
    {
        $columns = $columns ? (array)$columns : ['*'];

        if ($this->saving() === false) {
            return false;
        }

        $this->fillTimestamp();

        $this->_dal->put($columns);

        $this->_exist = true;

        // self::cache()->put($id, $this);

        if ($this->saved() === false) {
            return false;
        }

        return true;
    }

    /**
     * Delete the model.
     *
     * @return bool
     */
    public function delete()
    {
        if ($this->deleting() === false) {
            return false;
        }

        $this->_dal->delete();

        $cache = self::cache();
        $cache->forget($this->getId());

        if ($this->deleted() === false) {
            return false;
        }

        return true;
    }

    /**
     * @return static
     */
    final public static function createInstance()
    {
        return new static();
    }

    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     * @param array $columns
     * @param array $options
     * @return static
     */
    public static function find($id, array $columns = ['*'], $options = [])
    {
        // Return a cached instance if one exists
        $cache = self::cache();
        if ($cache->containsAttributes($id, $columns)) {
            return $cache->get($id);
        }

        // Return attributes which are not cached
        $columns = $cache->getNotCachedAttributes($id, $columns);

        // Create a new model
        $model = static::createInstance();
        $model->setId($id);

        // Merge options
        $options = array_merge($options, ['columns' => $columns == ['*'] ? [] : $columns]);

        // Get by id
        try {
            $model->_dal->get($id, $options);
        } catch (Missing404Exception $e) {
            return null;
        }

        // Fill internal attributes
        $model->_exist = true;

        // Put model to cache
        $model = $cache->put($id, $model, $columns);

        return $model;
    }

    /**
     * Find a model by its primary key or return new model.
     *
     * @param mixed $id
     * @return static
     */
    public static function findOrNew($id)
    {
        $model = static::find($id);
        if (is_null($model)) {
            $model = static::createInstance();
            $model->setId($id);
        }
        return $model;
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param mixed $id
     * @param array $columns
     * @param int $parentId
     * @return static
     */
    public static function findOrFail($id, array $columns = ['*'], $parentId = null)
    {
        $model = static::find($id, $columns, ['parent_id' => $parentId]);
        if (is_null($model)) {
            $reflect = new ReflectionClass(get_called_class());
            throw new ModelNotFoundException(sprintf(
                'Model `%s` not found by id `%s`', $reflect->getShortName(), $id
            ));
        }
        return $model;
    }

    /**
     * Save a new model and return the instance.
     *
     * @param array $attributes
     * @throws Exception
     * @return static
     */
    public static function create(array $attributes = [])
    {
        $model = static::createInstance();

        if (array_key_exists('id', $attributes)) {
            $model->setId($attributes['id']);
        }

        $model->fill($attributes);

        $model->save();

        return $model;
    }

    /**
     * Destroy the models by the given id.
     *
     * @param mixed $id
     */
    public static function destroy($id)
    {
        $ids = is_array($id) ? $id : [$id];
        foreach ($ids as $id) {
            $model = static::find($id);
            if (!is_null($model)) {
                $model->delete();
            }
        }
    }
}
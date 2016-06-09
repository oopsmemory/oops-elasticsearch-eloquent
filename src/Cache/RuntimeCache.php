<?php

namespace Isswp101\Persimmon\Cache;

use Isswp101\Persimmon\Model;

class RuntimeCache
{
    /**
     * Cache.
     *
     * @var array [
     *   'instance' => Model,
     *   'attributes' => []
     * ]
     */
    private $cache = [];

    /**
     * Return true if cache contains this key.
     *
     * @param mixed $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->cache);
    }

    /**
     * Return instance from cache.
     *
     * @param mixed $key
     * @return Model
     */
    public function get($key)
    {
        return $this->has($key) ? $this->cache[$key]['instance'] : null;
    }

    /**
     * Return all cache keys.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->cache);
    }

    /**
     * Put instance to cache.
     *
     * @param mixed $key
     * @param Model $instance
     * @param array $attributes
     * @return Model
     */
    public function put($key, Model $instance, array $attributes = ['*'])
    {
        if ($attributes != ['*'] && $this->has($key)) {
            $instance = Model::merge($this->cache[$key]['instance'], $instance, $attributes);
            $attributes = array_merge($this->cache[$key]['attributes'], $attributes);
        }

        $this->cache[$key] = [
            'instance' => $instance,
            'attributes' => $attributes
        ];

        return $instance;
    }

    /**
     * Return true if cache has already given attributes.
     *
     * @param mixed $key
     * @param array $attributes
     * @return bool
     */
    public function containsAttributes($key, array $attributes = ['*'])
    {
        return empty($this->getNotCachedAttributes($key, $attributes));
    }

    /**
     * Return the difference between given attributes and attributes which are already cached.
     *
     * @param mixed $key
     * @param array $attributes
     * @return array
     */
    public function getNotCachedAttributes($key, array $attributes = ['*'])
    {
        if (!$this->has($key)) {
            return $attributes;
        }
        $cachedAttributes = $this->cache[$key]['attributes'];
        return $cachedAttributes == ['*'] ? [] : array_diff($attributes, $cachedAttributes);
    }

    /**
     * Remove an item from the cache by key.
     *
     * @param mixed $key
     * @return $this
     */
    public function forget($key)
    {
        unset($this->cache[$key]);
        return $this;
    }
}

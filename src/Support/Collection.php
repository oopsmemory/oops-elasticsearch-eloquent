<?php

namespace Isswp101\Persimmon\Support;

use Illuminate\Support\Collection as LaravelCollection;

class Collection extends LaravelCollection
{
    /**
     * @var Where[]
     */
    protected $where = [];

    /**
     * Add a condition to filter items by the given key value pair.
     *
     * @param string $key
     * @param mixed $value
     * @param bool $strict
     * @return $this
     */
    public function addWhere($key, $value, $strict = true)
    {
        $this->where[] = new Where($key, $value, $strict);
        return $this;
    }

    /**
     * Add a condition to filter items by the given key value pair using loose comparison.
     *
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function addWhereLoose($key, $value)
    {
        return $this->addWhere($key, $value, false);
    }

    /**
     * Clear all conditions.
     *
     * @return $this
     */
    public function clearWhere()
    {
        $this->where = [];
        return $this;
    }

    /**
     * Return true if all conditions are met for the given item.
     *
     * @param $item
     * @return bool
     */
    protected function filteredByWhere($item)
    {
        $found = true;
        foreach ($this->where as $where) {
            if (
                !isset($item[$where->key]) ||
                ($where->strict ? $item[$where->key] !== $where->value : $item[$where->key] != $where->value)
            ) {
                $found = false;
                break;
            }
        }
        return $found;
    }

    /**
     * Update items according to the obtained conditions.
     *
     * @param string|array $key
     * @param mixed $value
     * @return $this
     */
    public function updateByWhere($key, $value = null)
    {
        $update = is_array($key) ? $key : [$key => $value];

        $this->transform(function ($item) use ($update) {
            if ($this->filteredByWhere($item)) {
                $item = array_merge($item, $update);
            }
            return $item;
        });

        $this->clearWhere();

        return $this;
    }

    /**
     * Filter items according to the obtained conditions.
     *
     * @param bool $keys
     * @return array
     */
    public function filterByWhere($keys = false)
    {
        $filtered = $this->filter(function ($item) {
            return $this->filteredByWhere($item);
        });

        $this->clearWhere();

        return $keys ? $filtered->all() : array_values($filtered->all());
    }

    /**
     * Get the first item from the collection according to the obtained conditions.
     *
     * @return mixed|null
     */
    public function firstByWhere()
    {
        $first = $this->first(function ($key, $item) {
            return $this->filteredByWhere($item);
        });

        $this->clearWhere();

        return $first;
    }

    /**
     * Get the last item from the collection according to the obtained conditions.
     *
     * @return mixed|null
     */
    public function lastByWhere()
    {
        $last = $this->last(function ($key, $item) {
            return $this->filteredByWhere($item);
        });

        $this->clearWhere();

        return $last;
    }
}
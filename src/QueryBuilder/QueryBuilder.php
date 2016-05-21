<?php

namespace Isswp101\Persimmon\QueryBuilder;

class QueryBuilder
{
    /**
     * Query.
     *
     * @var array
     */
    protected $query = [];

    /**
     * Constructor.
     *
     * @param array $body
     */
    public function __construct(array $body = [])
    {
        $this->query['body'] = $body;
    }

    /**
     * @param int $from
     * @return $this
     */
    public function from($from)
    {
        $this->query['from'] = $from;
        return $this;
    }

    /**
     * @param int $size
     * @return $this
     */
    public function size($size)
    {
        $this->query['size'] = $size;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasSort()
    {
        return !empty($this->query['body']['sort']);
    }

    /**
     * @param array|string $sort
     * @return $this
     */
    public function sort($sort)
    {
        $this->query['body']['sort'] = $sort;
        return $this;
    }

    /**
     * @param array|Filter $filter
     * @param string $mode
     * @return $this
     */
    public function filter($filter = [], $mode = 'include')
    {
        $map = [
            'include' => 'must',
            'exclude' => 'must_not',
            'should' => 'should'
        ];
        $mode = $map[$mode];

        if (!$filter) {
            unset($this->query['body']['filter']);
            return $this;
        }

        if ($filter instanceof Filter) {
            $filter = $filter->makeQuery();
        }

        $this->merge($filter, $mode);

        return $this;
    }

    /**
     * Specify the fields that will be retrieved when searching.
     *
     * @param mixed $fields
     * @return $this
     */
    public function fields($fields = false)
    {
        if ($fields === false) {
            $this->query['body']['_source'] = false;
        } elseif ((array)$fields == ['*']) {
            unset($this->query['body']['_source']);
        } else {
            $this->query['body']['_source'] = $fields;
        }
        return $this;
    }

    /**
     * Return only ids.
     *
     * @return $this
     */
    public function getOnlyIds()
    {
        return $this->fields(false);
    }

    /**
     * Return query.
     *
     * @return array
     */
    protected function getQuery()
    {
        return $this->query;
    }

    /**
     * Build query.
     *
     * @return array
     */
    public function build()
    {
        return $this->getQuery();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->getQuery(), JSON_PRETTY_PRINT);
    }

    /**
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->getQuery());
    }

    protected function merge(array $query, $mode = 'must')
    {
        if (!array_key_exists('filter', $this->query['body'])) {
            $this->query['body']['filter']['bool'][$mode] = [];
        }

        $this->query['body']['filter']['bool'][$mode][] = $query;
        return $this;
    }

    public function aggregation(Aggregation $aggregation)
    {
        $this->query['body']['aggs'][$aggregation->getName()] = $aggregation->make();
        return $this;
    }

    public function where($field, $value)
    {
        $query = ['term' => [$field => $value]];
        $this->merge($query);
        return $this;
    }

    public function notWhere($field, $value)
    {
        $query = ['term' => [$field => $value]];
        $this->merge($query, 'must_not');
        return $this;
    }

    public function orWhere($field, $value)
    {
        $query = ['term' => [$field => $value]];
        $this->merge($query, 'should');
        return $this;
    }

    public function between($field, $start, $end)
    {
        $query = ['range' => [$field => ['gt' => $start, 'lt' => $end]]];
        $this->merge($query);
        return $this;
    }

    public function betweenOrEquals($field, $start, $end)
    {
        $query = ['range' => [$field => ['gte' => $start, 'lte' => $end]]];
        $this->merge($query);
        return $this;
    }

    public function range($field, $start, $end)
    {
        return $this->betweenOrEquals($field, $start, $end);
    }

    public function greaterThan($field, $start)
    {
        $query = ['range' => [$field => ['gt' => $start]]];
        $this->merge($query);
        return $this;
    }

    public function gt($field, $start)
    {
        return $this->greaterThan($field, $start);
    }

    public function greaterThanOrEquals($field, $start)
    {
        $query = ['range' => [$field => ['gte' => $start]]];
        $this->merge($query);
        return $this;
    }

    public function gte($field, $start)
    {
        return $this->greaterThanOrEquals($field, $start);
    }

    public function lessThan($field, $end)
    {
        $query = ['range' => [$field => ['lt' => $end]]];
        $this->merge($query);
        return $this;
    }

    public function lt($field, $start)
    {
        return $this->lessThan($field, $start);
    }

    public function lessThanOrEquals($field, $end)
    {
        $query = ['range' => [$field => ['lte' => $end]]];
        $this->merge($query);
        return $this;
    }

    public function lte($field, $start)
    {
        return $this->lessThanOrEquals($field, $start);
    }

    public function match($field, $value)
    {
        $query = ['query' => ['match' => [$field => $value]]];
        $this->merge($query);
        return $this;
    }

    public function notMatch($field, $value)
    {
        $query = ['query' => ['match' => [$field => $value]]];
        $this->merge($query, 'must_not');
        return $this;
    }

    public function orMatch($field, $value)
    {
        $query = ['query' => ['match' => [$field => $value]]];
        $this->merge($query, 'should');
        return $this;
    }
}
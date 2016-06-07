<?php

namespace Isswp101\Persimmon\QueryBuilder\Filters;

abstract class Filter
{
    const MODE_INCLUDE = 'include';
    const MODE_EXCLUDE = 'exclude';
    const MODE_OFF = 'off';

    const MERGE_AND = 'AND';
    const MERGE_OR = 'OR';

    /**
     * Filter mode.
     *
     * @var string Can be as include, exclude or off.
     */
    protected $mode;

    /**
     * Filter values.
     *
     * @var mixed
     */
    protected $values;

    /**
     * This array contains linked instances of current filter.
     *
     * @var array
     */
    protected $linkedFilters = [];

    /**
     * Filters are merged using AND (must) by default.
     * But you can override it and merge them using OR (should).
     *
     * @var string
     */
    protected $mergeType = Filter::MERGE_AND;

    /**
     * Linked filters are merged using AND (must) by default.
     * But you can override it and merge them using OR (should).
     *
     * @var string
     */
    protected $logicalOperator = Filter::MERGE_OR;

    /**
     * Constructor.
     *
     * @param mixed $values Filter values.
     */
    public function __construct($values = null)
    {
        $this->values = $values;
    }

    /**
     * Set filter options.
     *
     * @param string $mode
     * @param string $logicalOperator
     * @param array $linkedFilters
     * @return $this
     */
    public function setOptions($mode = null, $logicalOperator = null, array $linkedFilters = [])
    {
        $this->mode = is_null($mode) ? Filter::MODE_INCLUDE : $mode;
        $this->logicalOperator = is_null($logicalOperator) ? Filter::MERGE_OR : $logicalOperator;
        $this->linkedFilters = $linkedFilters;
        return $this;
    }

    /**
     * Returns the actual elasticsearch query for one filter.
     * {
     *   "term": {
     *     "price": "0"
     *   }
     * }
     *
     * @param mixed $values
     * @return array
     */
    abstract public function query($values);

    /**
     * Returns wrapped elasticsearch filter query.
     * {
     *   "bool": {
     *     "should": [],
     *     "must_not": []
     *   }
     * }
     *
     * @return array
     */
    public function makeQuery()
    {
        $query = $this->query($this->getValues());

        $map = [
            Filter::MERGE_AND => 'must',
            Filter::MERGE_OR => 'should'
        ];

        if ($this->isInclude()) {
            if ($this->getLogicalOperator() == Filter::MERGE_AND) {
                $query = [
                    'bool' => [
                        'must' => [
                            $query
                        ]
                    ]
                ];
            } elseif ($this->getLogicalOperator() == Filter::MERGE_OR) {
                $query = [
                    'bool' => [
                        'should' => [
                            $query
                        ]
                    ]
                ];
            }
        } elseif ($this->isExclude()) {
            $mergeOperator = $map[$this->getLogicalOperator()];
            $query = [
                'bool' => [
                    $mergeOperator => [
                        [
                            'bool' => [
                                'must_not' => [
                                    $query
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        } elseif ($this->isOff()) {
            $query = [];
        }

        foreach ($this->getLinkedFilters() as $filter) {
            $extraQuery = $filter->makeQuery();

//            if ($filter->isInclude()) {
            if ($this->getLogicalOperator() == Filter::MERGE_AND) {
                $query = $this->mergeBoolQuery($query, $extraQuery, 'must');
            } elseif ($this->getLogicalOperator() == Filter::MERGE_OR) {
                $query = $this->mergeBoolQuery($query, $extraQuery, 'should');
            }
//            }
//            elseif ($filter->isExclude()) {
//                $query = $this->mergeBoolQuery($query, $extraQuery, 'must_not');
//            }
        }

        return $query;
    }

    /**
     * Merges elastcisearch query with current filter query.
     *
     * @param array $query Elastcisearch query.
     * @return array Merged elasticsearch query.
     */
    public function mergeQuery(array $query)
    {
        $types = [
            Filter::MERGE_AND => 'must',
            Filter::MERGE_OR => 'should'
        ];

        $type = $this->getMergeType();

        $query['body']['filter']['bool'][$types[$type]][] = $this->makeQuery();

        return $query;
    }

    /**
     * Returns filter mode.
     *
     * @return string Can be as include, exclude or off.
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Returns true if mode is include.
     *
     * @return bool
     */
    public function isInclude()
    {
        return $this->getMode() == Filter::MODE_INCLUDE;
    }

    /**
     * Returns true if mode is exclude.
     *
     * @return bool
     */
    public function isExclude()
    {
        return $this->getMode() == Filter::MODE_EXCLUDE;
    }

    /**
     * Returns true if mode is off.
     *
     * @return bool
     */
    public function isOff()
    {
        return $this->getMode() == Filter::MODE_OFF;
    }

    /**
     * Retuns filter values as array.
     *
     * @return mixed
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Returns linked instances of current filter.
     *
     * @return Filter[]
     */
    public function getLinkedFilters()
    {
        return $this->linkedFilters;
    }

    /**
     * Returns true if filter has linked filters.
     *
     * @return bool
     */
    public function hasLinkedFilters()
    {
        return !empty($this->linkedFilters);
    }

    /**
     * Returns filter merge type.
     *
     * @return string - "AND" || "OR"
     */
    public function getMergeType()
    {
        return $this->mergeType;
    }

    /**
     * Sets filter merge type.
     *
     * @param string $mergeType - "AND" || "OR"
     */
    public function setMergeType($mergeType)
    {
        $this->mergeType = $mergeType;
    }

    /**
     * Returns linked filters logical operator.
     *
     * @return string - "AND" || "OR"
     */
    public function getLogicalOperator()
    {
        return $this->logicalOperator;
    }

    /**
     * Updates linked filters logical operator.
     *
     * @param string $logicalOperator - "AND" || "OR"
     */
    public function setLogicalOperator($logicalOperator)
    {
        $this->logicalOperator = $logicalOperator;
    }

    /**
     * Returns true if merge type is valid.
     * @return bool
     */
    public function isMergeTypeValid()
    {
        return in_array($this->mergeType, [Filter::MERGE_AND, Filter::MERGE_OR]);
    }

    /**
     * Merges BOOL elasticsearch queries.
     *
     * @param array $query1
     * @param array $query2
     * @param string $type must, must_not, should
     * @return array
     */
    protected function mergeBoolQuery(array $query1, array $query2, $type)
    {
        if (empty($query2['bool'][$type])) {
            return $query1;
        } else {
            if (empty($query1['bool'][$type])) {
                $query1['bool'][$type] = [];
            }
        }

        $query1['bool'][$type] = array_merge($query1['bool'][$type], $query2['bool'][$type]);

        return $query1;
    }
}

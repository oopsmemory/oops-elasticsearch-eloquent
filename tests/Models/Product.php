<?php

namespace Isswp101\Persimmon\Tests\Models;

use Isswp101\Persimmon\Models\BaseElasticsearchModel;

/**
 * @property string name
 * @property int price
 */
final class Product extends BaseElasticsearchModel
{
    protected string $index = 'index';

    protected int $perRequest = 5;
}

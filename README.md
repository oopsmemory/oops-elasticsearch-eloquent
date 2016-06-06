# Persimmon / Elasticsearch Eloquent (Beta)

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

TBD.

## Install

Via Composer

``` bash
$ composer require isswp101/elasticsearch-eloquent
```

## Usage

### Create a new model

You must specify static `index` and `type` variables to determine the document path.

```php
use Isswp101\Persimmon\ElasticsearchModel;

class Product extends ElasticsearchModel
{
    protected static $index = 'test';
    protected static $type = 'test';

    public $name;
    public $price = 0;
}
```

Here `name` and `price` are fields which will be stored in Elasticsearch.  
Use the static `create()` method to create document in Elasticsearch:

```php
$product = Product::create(['id' => 3, 'name' => 'Product 3', 'price' => 30]);
```

### Save the model

```php
$product = new Product();
$product->id = 1;
$product->name = 'Product 1';
$product->price = 20;
$product->save();
```

Use `save()` method to store model data in Elasticsearch. Let's see how this looks in Elasticsearch:

```json
{
   "_index": "test",
   "_type": "test",
   "_id": "1",
   "_version": 1,
   "found": true,
   "_source": {
      "name": "Product 1",
      "price": 10,
      "id": 1,
      "user_id": null,
      "created_at": "2016-06-03 08:11:08",
      "updated_at": "2016-06-03 08:11:08"
   }
}
```

Fields `created_at` and `updated_at` were created automatically. The `user_id` field is persistent field to store user id.

### Find existing model

```php
$product = Product::find(1);
```

If you have big data in Elasticsearch you can specify certain fields to retrieve:

```php
$product = Product::find(1, ['name']);
```

In this case the `price` field equals `0` because it's populated as the default value that you specified in the model.

There are the following methods:
* `findOrFail()` returns `ModelNotFoundException` exception if no result found.
* `findOrNew()` returns a new model if no result found.

### Model cache

There is a smart model cache when you use methods like `find()`, `findOrFail()` and so on.

```php
$product = Product::find(1, ['name']);  // will be retrieved from the elasticsearch
$product = Product::find(1, ['name']);  // will be retrieved from the cache
$product = Product::find(1, ['price']); // elasticsearch
$product = Product::find(1, ['price']); // cache
$product = Product::find(1, ['name']);  // cache
```

```php
$product = Product::findOrFail(1);      // elasticsearch
$product = Product::find(1);            // cache
$product = Product::find(1, ['name']);  // cache
$product = Product::find(1, ['price']); // cache
```

### Partial update

You can use partial update to update specific fields quickly.

```php
$product = Product::find(1, ['name']);
$product->name = 'Product 3';
$product->save('name');
```

### Delete models

```php
$product = Product::find(1);
$product->delete();
```

You can use the static method:

```php
Product::destroy(1);
```

### Basic search

There are helpers to search documents:
* `first($query)` returns the first document according to the query or `null`.
* `firstOrFail($query)` returns `ModelNotFoundException` exception if `first($query)` returns `null`.
* `search($query)` returns documents (default 50 items) according to the query.
* `map($query, callable $callback)` returns all documents (default 50 items per request) according to the query.
* `all($query)` returns all documents according to the query.

If `$query` is not passed the query will be as `match_all` query.

### Query Builder

```php
use Isswp101\Persimmon\QueryBuilder\QueryBuilder;

$query = new QueryBuilder();
```

Simple usage:

```php
$query = new QueryBuilder(['query' => ['match' => ['name' => 'Product']]]);
$products = Product::search($query);
```

The `match` query:

```php
$query = new QueryBuilder();
$query->match('name', 'Product');
$products = Product::search($query);    
```

The `range` query:

```php
$query = new QueryBuilder();
$query->betweenOrEquals('price', 20, 30)->greaterThan('price', 15);
$products = Product::search($query);
```

The `TermFilter` filter:

```php
$query = new QueryBuilder();
$query->filter(new TermFilter('name', '2'));
$products = Product::search($query);
```

The `IdsFilter` filter:

```php
$query = new QueryBuilder();
$query->filter(new IdsFilter([1, 3]));
$products = Product::search($query);
```

**TO BE CONTINUED...**

@TODO:
* Add parent child relationship
* Add aggregations
* Add `RangeOrExistFilter` filter

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email isswp101@gmail.com instead of using the issue tracker.

## Credits

- [Sergey Sorokin][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/isswp101/elasticsearch-eloquent.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/isswp101/elasticsearch-eloquent/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/isswp101/elasticsearch-eloquent.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/isswp101/elasticsearch-eloquent.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/isswp101/elasticsearch-eloquent.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/isswp101/elasticsearch-eloquent
[link-travis]: https://travis-ci.org/isswp101/elasticsearch-eloquent
[link-scrutinizer]: https://scrutinizer-ci.com/g/isswp101/elasticsearch-eloquent/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/isswp101/elasticsearch-eloquent
[link-downloads]: https://packagist.org/packages/isswp101/elasticsearch-eloquent
[link-author]: https://github.com/isswp101
[link-contributors]: ../../contributors

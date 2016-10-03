# middlewares/cache

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-scrutinizer]][link-scrutinizer]
[![Total Downloads][ico-downloads]][link-downloads]
[![SensioLabs Insight][ico-sensiolabs]][link-sensiolabs]

Middleware components with the following cache utilities:

* CachePrevention
* Expires
* Cache

## Requirements

* PHP >= 5.6
* A [PSR-7](https://packagist.org/providers/psr/http-message-implementation) http mesage implementation ([Diactoros](https://github.com/zendframework/zend-diactoros), [Guzzle](https://github.com/guzzle/psr7), [Slim](https://github.com/slimphp/Slim), etc...)
* A [PSR-15](https://github.com/http-interop/http-middleware) middleware dispatcher ([Middleman](https://github.com/mindplay-dk/middleman), etc...)

## Installation

This package is installable and autoloadable via Composer as [middlewares/cache](https://packagist.org/packages/middlewares/cache).

```sh
composer require middlewares/cache
```

## CachePrevention

To add the response headers for cache prevention. Useful in development environments:

```php
$dispatcher = new Dispatcher([
    new Middlewares\CachePrevention()
]);

$response = $dispatcher->dispatch(new Request());
```

## Expires

To add the `Expires` and `Cache-Control: max-age` headers to the response.

#### `__construct(array $expires = null)`

Set the available expires for each mimetype. If it's not defined, [use the defaults](src/expires_defaults.php).

#### `defaultExpires(string $expires)`

Set the default expires value if the request mimetype does not match. By default is 1 month. Example:

```php
$dispatcher = new Dispatcher([
    (new Middlewares\Expires([ //set 1 year lifetime to css and js
        'text/css' => '+1 year',
        'text/javascript' => '+1 year',
    ]))->defaultExpires('+1 hour') //and 1 hour to everything else
]);

$response = $dispatcher->dispatch(new Request());
```

## Cache

Saves the response headers in a [PSR-6 cache pool](http://www.php-fig.org/psr/psr-6/) and returns `304` responses (Not modified) if the response is still valid. This saves server resources and bandwidth because the body is returned empty. It's recomended to combine it with `Expires` to set the lifetime of the responses.

#### `__construct(Psr\Cache\CacheItemPoolInterface $cache)`

The cache pool instance used to save the responses headers.

```php
$dispatcher = new Dispatcher([
    new Middlewares\Cache($cache),
    new Middlewares\Expires()
]);

$response = $dispatcher->dispatch(new Request());
```

---

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes and [CONTRIBUTING](CONTRIBUTING.md) for contributing details.

The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.

[ico-version]: https://img.shields.io/packagist/v/middlewares/cache.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/middlewares/cache/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/g/middlewares/cache.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/middlewares/cache.svg?style=flat-square
[ico-sensiolabs]: https://img.shields.io/sensiolabs/i/36786f5a-2a15-4399-8817-8f24fcd8c0b4.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/middlewares/cache
[link-travis]: https://travis-ci.org/middlewares/cache
[link-scrutinizer]: https://scrutinizer-ci.com/g/middlewares/cache
[link-downloads]: https://packagist.org/packages/middlewares/cache
[link-sensiolabs]: https://insight.sensiolabs.com/projects/36786f5a-2a15-4399-8817-8f24fcd8c0b4

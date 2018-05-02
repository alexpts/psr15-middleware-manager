# psr15-middleware-manager

[![Build Status](https://travis-ci.org/alexpts/psr15-middleware-manager.svg?branch=master)](https://travis-ci.org/alexpts/psr15-middleware-manager)
[![Code Coverage](https://scrutinizer-ci.com/g/alexpts/psr15-middleware-manager/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/alexpts/psr15-middleware-manager/?branch=master)
[![Code Climate](https://codeclimate.com/github/alexpts/psr15-middleware-manager/badges/gpa.svg)](https://codeclimate.com/github/alexpts/psr15-middleware-manager)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexpts/psr15-middleware-manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexpts/psr15-middleware-manager/?branch=master)


Middleware manager compatible with the [PSR-7](https://www.php-fig.org/psr/psr-7/) and [PSR-15](https://www.php-fig.org/psr/psr-15/)

## Installation

```$ composer require alexpts/psr15-middleware-manager```


## Example
```php
use PTS\PSR15\MiddlewareManager\MiddlewareManager;

$manager = new MiddlewareManager;

$manager
	->push(new RequestWithAttribute(['container' => $container]))
	->push(new RouterMiddleware);

$manager->handle($request);
```

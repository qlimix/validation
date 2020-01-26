# validation

[![Travis CI](https://api.travis-ci.org/qlimix/validation.svg?branch=master)](https://travis-ci.org/qlimix/validation)
[![Coveralls](https://img.shields.io/coveralls/github/qlimix/validation.svg)](https://coveralls.io/github/qlimix/validation)
[![Mutation testing badge](https://badge.stryker-mutator.io/github.com/qlimix/validation/master)](https://stryker-mutator.github.io)
[![Packagist](https://img.shields.io/packagist/v/qlimix/validation.svg)](https://packagist.org/packages/qlimix/validation)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/qlimix/validation/blob/master/LICENSE)

Validate data individually or by set.

## Install

Using Composer:

~~~
$ composer require qlimix/validation
~~~

## usage

### Example 1 Collection
```json
[
    {
        "foo": "bar",
        "foobar": {
            "bar": 1,
            "foo": "example"
        }
    },
    {
        "foo": "foo",
        "foobar": {
            "bar": 2,
            "foo": "example1"
        }
    }
]
```

```php
<?php

use Qlimix\Validation\CollectionValidation;
use Qlimix\Validation\Inspector\HashInspector;
use Qlimix\Validation\Inspector\KeyInspector;
use Qlimix\Validation\Key;

$example1 = new CollectionValidation([
    new HashInspector([new Key('foo', true, [])]), // add validators
    new KeyInspector('foobar', true, [
        new HashInspector([
            new Key('bar', true, []), // add validators
            new Key('foo', true, []), // add validators
        ])
    ])
]);
```

### Example 2 key values
```json
{
    "foo": "foobar",
    "foobar": {
        "bar": 42,
        "foo": "example2"
    }
}
```

```php
<?php

use Qlimix\Validation\Inspector\HashInspector;
use Qlimix\Validation\Inspector\KeyInspector;
use Qlimix\Validation\InspectorValidation;
use Qlimix\Validation\Key;

$example2 = new InspectorValidation([
    new HashInspector([new Key('foo', true, [])]), // add validators
    new KeyInspector('foobar', true, [
        new HashInspector([
            new Key('bar', true, []), // add validators
            new Key('bar', true, []), // add validators
        ])
    ])
]);
```

## Testing
To run all unit tests locally with PHPUnit:

~~~
$ vendor/bin/phpunit
~~~

## Quality
To ensure code quality run grumphp which will run all tools:

~~~
$ vendor/bin/grumphp run
~~~


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

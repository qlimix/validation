# validation

[![Travis CI](https://api.travis-ci.org/qlimix/validation.svg?branch=master)](https://travis-ci.org/qlimix/validation)
[![Coveralls](https://img.shields.io/coveralls/github/qlimix/validation.svg)](https://coveralls.io/qlimix/validation)
[![Packagist](https://img.shields.io/packagist/v/qlimix/validation.svg)](https://packagist.org/packages/qlimix/validation)
[![MIT License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](https://github.com/qlimix/validation/blob/master/LICENSE)

Validate data individually or by set.

## Install

Using Composer:

~~~
$ composer require qlimix/validation
~~~

## usage

```php
<?php
use Qlimix\Validation\CollectionValidation;
use Qlimix\Validation\HashValidation;
use Qlimix\Validation\Hash\Key;
use Qlimix\Validation\Validator\CollectionValidator;
$collectionValidation = new CollectionValidation(new HashValidation(
    [
        new Key('test3', true, [])
    ],
    []
));

$validation = new HashValidation(
    [
        new Key('test1', true, []),
        new Key('test2', true, [
            new CollectionValidator($collectionValidation)
        ]),
    ],
    []
);
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

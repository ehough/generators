# ehough/generators

[![Build Status](https://travis-ci.org/ehough/generators.svg?branch=develop)](https://travis-ci.org/ehough/generators)
[![Code Coverage](https://scrutinizer-ci.com/g/ehough/generators/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/ehough/generators/?branch=develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ehough/generators/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/ehough/generators/?branch=develop)
[![Latest Stable Version](https://poser.pugx.org/ehough/generators/v/stable)](https://packagist.org/packages/ehough/generators)
[![License](https://poser.pugx.org/ehough/generators/license)](https://packagist.org/packages/ehough/generators)


Easily backport [generators](http://php.net/manual/en/language.generators.overview.php) to PHP 5.3 and 5.4.

## Why?

Generators were introduced to PHP in version 5.5, but sadly [60%](https://w3techs.com/technologies/details/pl-php/5/all) of all PHP web servers still run PHP 5.4 and lower. This library makes it (relatively) easy to backport code that relies on generators for use on legacy systems.

This library will be retired once PHP 5.5+ levels reach 85%. If you don't need to backport code to PHP 5.3 or 5.4, you don't need this library.


## Quick Start

Say you need to use the following code on PHP 5.3:

```php
$generator = function ($values) {
    print "Let's get started\n";
    foreach ($values as $key => $value) {
        yield $key => $value;
    }
    print "Nothing more to do\n";
};

$items = array('foo' => 'bar', 'some' => 'thing');

foreach ($generator($items) as $k => $v) {
    print "The generator gave us $k => $v\n";
}
```
The above code results in:
```
Let's get started
The generator gave us foo => bar
The generator gave us some => thing
Nothing more to do
```
Since the code above uses generators, it won't run on PHP 5.4 or lower. This library provides you with the `AbstractGenerator` class, which requires you to implement `resume($position)`.  `$position` is incremented each time the generator resumes execution, and you can use the position to determine which part of the generator to run. So the above generator could be rewritten as:


```php
use Hough\Generators\AbstractGenerator

class MyGenerator extends \Hough\Promise\AbstractGenerator
{
    private $keys;
    private $values;

    public function __construct(array $items)
    {
        $this->keys   = array_keys($items);
        $this->values = array_values($items);
    }

    protected function resume($position)
    {
        // first execution
        if ($position === 0) {
            print "Let's get started\n";
        }

        // still inside the for loop
        if ($position < count($this->values)) {

            // return an array of two items: the first is the yielded key, the second is the yielded value
            return array(
                $this->keys[$position],
                $this->values[$position]
            );
        }

        // we must be done with the for loop, so print our last statement and return null to signal we're done
        print "Nothing more to do\n";
        return null;
    }
}

$items = array('foo' => 'bar', 'some' => 'thing');
foreach (new MyGenerator($items) as $k => $v) {
    print "The generator gave us $k => $v\n";
}
```
The above code results in:
```
Let's get started
The generator gave us foo => bar
The generator gave us some => thing
Nothing more to do
```

The code is not nearly as clean and simple, but any generator can be rewritten using this library.

## Yielding Keys and Values

You have three choices for what you return from `resume($position)`:

 1. If you return null, you are signaling that there are no more statements inside the generator. The generator will be considered to be closed at this point.
 2. If you return an array with two values, the first element is interpreted to be the yielded key and the second value is the yielded value.
 3. If you return an array with one value, it is interpreted to be a yielded value, and `$position` will be used as the key.

## Accessing Sent Values

You can access the last value sent in from the caller with `getLastValueSentIn()`. This might be `null`.

## Handling Exceptions

By default, if an exception is thrown into the generator (via `throw(\Exception $e)`) it will be rethrown back to the calling context. If you'd like to "catch" these exceptions, you can override `onExceptionThrownIn(\Exception $e)` and swallow or otherwise handle the exception.

# DeepEnd

A simple order tracker for dependent task execution.

The goal is to help you build a graph of dependent entities, say task identifiers, which can then be sorted to an order that is a correct execution order, ie. if task B depends on task A then task A comes before task B.

As a hypothetical example imagine that you work on a test case. There are 3 steps involved: setup, test and teardown. In the setup phase you create a bunch of different DB entries. You then run your test. Then when you want to delete those entries you've created you get a foreign key constraints error. In order to be able to delete them you need to know the correct order. DeepEnd makes this easy.
 
## Requirements

PHP 7.1+

## Installation

    $ composer require pwm/deepend

## Usage

```php
// Create an empty store
$deepEnd = new DeepEnd();

// Add some entries, in this case they are task ids
$deepEnd->add('t1');
$deepEnd->add('t2');
$deepEnd->add('t3');
$deepEnd->add('t4');

// Specify dependencies between them. A pointing to B, ie. an arrow from A to B,
// means that B depends on A therefore A has to happen before B can happen.
$deepEnd->draw((new Arrow)->from('t1')->to('t2'));
$deepEnd->draw((new Arrow)->from('t1')->to('t3'));
$deepEnd->draw((new Arrow)->from('t2')->to('t4'));

// Get a valid execution order
$order = $deepEnd->sort(); // ['t1', 't3', 't2', 't4']
```

## Test

	$ vendor/bin/phpunit

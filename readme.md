# DeepEnd

[![Build Status](https://travis-ci.org/pwm/deepend.svg?branch=master)](https://travis-ci.org/pwm/deepend)
[![Maintainability](https://api.codeclimate.com/v1/badges/5f290f7ca04ac4f032e1/maintainability)](https://codeclimate.com/github/pwm/deepend/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/5f290f7ca04ac4f032e1/test_coverage)](https://codeclimate.com/github/pwm/deepend/test_coverage)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

A library for scheduling dependent task execution.

The goal is to be able to build a network of dependent entities, eg. task ids, which can then be sorted into a correct execution order. This means that if task B depends on task A then task A comes before task B in the sorted order.

## Requirements

PHP 7.1+

## Installation

    $ composer require pwm/deepend

## Usage

Below is a simple example of creating dependencies between 4 tasks:

```php
// Create an empty store
$deepEnd = new DeepEnd();

// Add some entries, in our example they are the task ids
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
$order = $deepEnd->sort(); // $order = ['t1', 't3', 't2', 't4']
```

You can also add data (including functions and objects) to your tasks for later use. For this case use the `sortToMap()` method, which returns a map with ids as keys and the added data as values. Using the above example of 4 tasks we could do the following:

```php
function taskRunner(string $taskId, string $taskData): void
{
    echo sprintf('Running task %s with data %s ... done.', $taskId, $taskData) . PHP_EOL;
}

$deepEnd = new DeepEnd();

$deepEnd->add('t1', 't1-data');
$deepEnd->add('t2', 't2-data');
$deepEnd->add('t3', 't3-data');
$deepEnd->add('t4', 't4-data');

$deepEnd->draw((new Arrow)->from('t1')->to('t2'));
$deepEnd->draw((new Arrow)->from('t1')->to('t3'));
$deepEnd->draw((new Arrow)->from('t2')->to('t4'));

$orderedTasks = $deepEnd->sortToMap();
foreach ($orderedTasks as $taskId => $taskData) {
    taskRunner($taskId, $taskData);
}

// Running task t1 with data t1-data ... done.
// Running task t3 with data t3-data ... done.
// Running task t2 with data t2-data ... done.
// Running task t4 with data t4-data ... done.
```

## How it works

When adding entries via the `add()` method and arrows via the `draw()` method you are actually building a graph, more specifically a directed acyclic graph (DAG), where nodes are added via `add()` and edges/arrows via `draw()`.

Every time you want to draw a new arrow from node A to node B DeepEnd checks whether it would create a cycle and if the answer is yes then it will tell you in the form of an exception. The way this is done is that, before drawing the arrow, DeepEnd checks whether A is already reachable from B, ie. whether there's a path from B to A. If there is, then an arrow from A to B would lead to a cycle as you can already get from B to A and then, via the new arrow, back to B.

Once you have your graph built the `sort()` method can be used to do what is called a topological sort on your DAG. This is done using depth first search where DeepEnd incrementally indexes nodes as it leaves them after visiting. This is known as postordering. Once finished it will sort the nodes in reverse order, starting with nodes with the highest post order index. This results in an order where nodes that depend on other nodes come after their dependencies, which is the order we are after.

## Tests

	$ vendor/bin/phpunit

## Changelog

[Click here](changelog.md)

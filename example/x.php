<?php
declare(strict_types=1);

use Pwm\DeepEnd\Arrow;
use Pwm\DeepEnd\DeepEnd;
use Pwm\DeepEnd\Node;

require_once __DIR__.'/../vendor/autoload.php';

$deepEnd = new DeepEnd();
$deepEnd->add((new Arrow)->from(new Node('5'))->to(new Node('6')));
$deepEnd->add((new Arrow)->from(new Node('3'))->to(new Node('4')));
$deepEnd->add((new Arrow)->from(new Node('6'))->to(new Node('4')));
$deepEnd->add((new Arrow)->from(new Node('8'))->to(new Node('9')));
$deepEnd->add((new Arrow)->from(new Node('7'))->to(new Node('8')));
$deepEnd->add((new Arrow)->from(new Node('3'))->to(new Node('8')));
$deepEnd->add((new Arrow)->from(new Node('6'))->to(new Node('2')));
$deepEnd->add((new Arrow)->from(new Node('6'))->to(new Node('9')));
$deepEnd->add((new Arrow)->from(new Node('7'))->to(new Node('6')));

echo implode(', ', $deepEnd->sort());

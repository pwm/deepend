<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

class Arrow
{
    /** @var Node */
    private $fromNode;

    /** @var Node */
    private $toNode;

    public function __construct(Node $fromNode = null, Node $toNode = null)
    {
        $this->fromNode = $fromNode;
        $this->toNode = $toNode;
    }

    public function from(Node $node): Arrow
    {
        $this->fromNode = $node;
        return $this;
    }

    public function to(Node $node): Arrow
    {
        $this->toNode = $node;
        return $this;
    }

    public function getFrom(): Node
    {
        return $this->fromNode;
    }

    public function getTo(): Node
    {
        return $this->toNode;
    }
}

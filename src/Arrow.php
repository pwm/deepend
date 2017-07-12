<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

use Pwm\DeepEnd\Exception\IncompleteArrow;

class Arrow
{
    /** @var string */
    private $fromNodeId;

    /** @var string */
    private $toNodeId;

    public function from(string $nodeId): Arrow
    {
        $this->fromNodeId = $nodeId;
        return $this;
    }

    public function to(string $nodeId): Arrow
    {
        $this->toNodeId = $nodeId;
        return $this;
    }

    public function getFromNodeId(): string
    {
        self::ensureNodeIsSet($this->fromNodeId);
        return $this->fromNodeId;
    }

    public function getToNodeId(): string
    {
        self::ensureNodeIsSet($this->toNodeId);
        return $this->toNodeId;
    }

    private static function ensureNodeIsSet(?string $nodeId): void
    {
        if ($nodeId === null) {
            throw new IncompleteArrow('An arrow must have "from" and "to" nodes to be drawable.');
        }
    }
}

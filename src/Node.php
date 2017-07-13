<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

class Node
{
    /** @var string */
    private $id;

    /** @var null|mixed */
    private $data;

    /** @var bool */
    private $visited = false;

    /** @var int */
    private $index = 0;

    public function __construct(string $id, /* mixed */ $data = null)
    {
        $this->id = $id;
        $this->data = $data;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getData()
    {
        return $this->data;
    }

    public function visited(): bool
    {
        return $this->visited;
    }

    public function visit(): void
    {
        $this->visited = true;
    }

    public function unvisit(): void
    {
        $this->visited = false;
    }

    public function getIndex(): int
    {
        return $this->index;
    }

    public function setIndex(int $index): void
    {
        $this->index = $index;
    }
}

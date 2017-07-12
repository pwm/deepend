<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    /**
     * @test
     */
    public function node_can_be_created(): void
    {
        $node = new Node('id');
        static::assertInstanceOf(Node::class, $node);
        static::assertSame('id', $node->getId());
        static::assertNull($node->getData());
        static::assertFalse($node->visited());
        static::assertSame(0, $node->getIndex());
    }

    /**
     * @test
     */
    public function node_can_be_created_with_data(): void
    {
        $node = new Node('id', 'data');
        static::assertSame('data', $node->getData());
    }

    /**
     * @test
     */
    public function node_can_be_visited_and_unvisited(): void
    {
        $node = new Node('id');
        static::assertFalse($node->visited());
        $node->visit();
        static::assertTrue($node->visited());
        $node->unvisit();
        static::assertFalse($node->visited());
    }

    /**
     * @test
     */
    public function node_index_can_be_set(): void
    {
        $node = new Node('id');
        static::assertSame(0, $node->getIndex());
        $node->setIndex(1);
        static::assertSame(1, $node->getIndex());
    }
}

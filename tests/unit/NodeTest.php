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
        self::assertInstanceOf(Node::class, $node);
        self::assertSame('id', $node->getId());
        self::assertNull($node->getData());
        self::assertFalse($node->visited());
        self::assertSame(0, $node->getIndex());
    }

    /**
     * @test
     */
    public function node_can_be_created_with_data(): void
    {
        $node = new Node('id', 'data');
        self::assertSame('data', $node->getData());
    }

    /**
     * @test
     */
    public function node_can_be_visited_and_unvisited(): void
    {
        $node = new Node('id');
        self::assertFalse($node->visited());
        $node->visit();
        self::assertTrue($node->visited());
        $node->unvisit();
        self::assertFalse($node->visited());
    }

    /**
     * @test
     */
    public function node_index_can_be_set(): void
    {
        $node = new Node('id');
        self::assertSame(0, $node->getIndex());
        $node->setIndex(1);
        self::assertSame(1, $node->getIndex());
    }
}

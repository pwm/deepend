<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

use PHPUnit\Framework\TestCase;

class ArrowTest extends TestCase
{
    /**
     * @test
     */
    public function arrow_can_be_created(): void
    {
        $arrow = new Arrow;
        static::assertInstanceOf(Arrow::class, $arrow);
    }

    /**
     * @test
     */
    public function from_and_to_nodes_can_be_set(): void
    {
        $arrow = new Arrow;
        $arrow->from('from');
        $arrow->to('to');

        static::assertSame('from', $arrow->getFromNodeId());
        static::assertSame('to', $arrow->getToNodeId());
    }

    /**
     * @test
     */
    public function node_setting_is_chainable(): void
    {
        $arrow = (new Arrow)->from('from')->to('to');

        static::assertSame('from', $arrow->getFromNodeId());
        static::assertSame('to', $arrow->getToNodeId());
    }

    /**
     * @test
     * @expectedException \Pwm\DeepEnd\Exception\IncompleteArrow
     */
    public function get_from_node_enforces_its_value_to_be_set(): void
    {
        $arrow = (new Arrow)->to('to');
        $arrow->getFromNodeId();
    }

    /**
     * @test
     * @expectedException \Pwm\DeepEnd\Exception\IncompleteArrow
     */
    public function get_to_node_enforces_its_value_to_be_set(): void
    {
        $arrow = (new Arrow)->from('from');
        $arrow->getToNodeId();
    }
}

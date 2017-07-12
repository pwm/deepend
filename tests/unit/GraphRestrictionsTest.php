<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

use PHPUnit\Framework\TestCase;

class GraphRestrictionsTest extends TestCase
{
    /**
     * @test
     * @expectedException \Pwm\DeepEnd\Exception\NodeAlreadyPresent
     */
    public function cannot_add_nodes_with_the_same_id_twice(): void
    {
        $deepEnd = new DeepEnd();
        $deepEnd->add('1');
        $deepEnd->add('1');
    }

    /**
     * @test
     * @expectedException \Pwm\DeepEnd\Exception\NodeDoesNotExist
     */
    public function cannot_draw_arrows_between_nonexistent_nodes(): void
    {
        $deepEnd = new DeepEnd();
        $deepEnd->draw((new Arrow)->from('from')->to('to'));
    }

    /**
     * @test
     * @expectedException \Pwm\DeepEnd\Exception\NodeDoesNotExist
     */
    public function cannot_draw_arrow_from_an_existing_node_to_a_nonexistent_node(): void
    {
        $deepEnd = new DeepEnd();
        $deepEnd->add('from');
        $deepEnd->draw((new Arrow)->from('from')->to('to'));
    }

    /**
     * @test
     * @expectedException \Pwm\DeepEnd\Exception\NodeDoesNotExist
     */
    public function cannot_draw_arrow_from_a_nonexistent_node_to_an_existing_node(): void
    {
        $deepEnd = new DeepEnd();
        $deepEnd->add('to');
        $deepEnd->draw((new Arrow)->from('from')->to('to'));
    }

    /**
     * @test
     * @expectedException \Pwm\DeepEnd\Exception\CycleDetected
     */
    public function cannot_build_a_cyclic_graph(): void
    {
        $deepEnd = new DeepEnd();
        $deepEnd->add('a');
        $deepEnd->add('b');
        $deepEnd->draw((new Arrow)->from('a')->to('b'));
        $deepEnd->draw((new Arrow)->from('b')->to('a'));
    }
}

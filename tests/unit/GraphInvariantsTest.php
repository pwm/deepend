<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

use PHPUnit\Framework\TestCase;
use Pwm\DeepEnd\Exception\CycleDetected;

class GraphInvariantsTest extends TestCase
{
    /**
     * @test
     */
    public function order_zero_graphs_sort_to_empty_arrays(): void
    {
        $deepEnd = new DeepEnd();
        self::assertSame([], $deepEnd->sort());
    }

    /**
     * @test
     */
    public function single_node_graphs_sort_to_single_node_id_arrays(): void
    {
        $deepEnd = new DeepEnd();
        $deepEnd->add('a');
        self::assertSame(['a'], $deepEnd->sort());
    }

    /**
     * @test
     */
    public function edgeless_graphs_sort_to_reverse_add_order(): void
    {
        $deepEnd = new DeepEnd();
        $deepEnd->add('a');
        $deepEnd->add('b');
        self::assertSame(['b', 'a'], $deepEnd->sort());

        $deepEnd = new DeepEnd();
        $deepEnd->add('b');
        $deepEnd->add('a');
        self::assertSame(['a', 'b'], $deepEnd->sort());

        $a = range(0, 999);
        shuffle($a);
        $a = array_map(function ($e) { return (string)$e; }, $a);

        $deepEnd = new DeepEnd();
        foreach ($a as $e) {
            $deepEnd->add($e);
        }

        self::assertSame(array_reverse($a), $deepEnd->sort());
    }

    /**
     * @test
     */
    public function connected_nodes_sort_from_source_to_sink_regardless_of_add_order(): void
    {
        $deepEnd = new DeepEnd();
        $deepEnd->add('source');
        $deepEnd->add('sink');
        $deepEnd->draw((new Arrow)->from('source')->to('sink'));
        self::assertSame(['source', 'sink'], $deepEnd->sort());

        $deepEnd = new DeepEnd();
        $deepEnd->add('sink');
        $deepEnd->add('source');
        $deepEnd->draw((new Arrow)->from('source')->to('sink'));
        self::assertSame(['source', 'sink'], $deepEnd->sort());
    }

    /**
     * @test
     */
    public function disconnected_components_sort_in_reverse_add_order_by_components_and_source_to_sink_per_component(): void
    {
        $deepEnd = new DeepEnd();
        $deepEnd->add('a');
        $deepEnd->add('b');
        $deepEnd->add('c');
        $deepEnd->add('d');
        $deepEnd->add('e');
        $deepEnd->add('f');
        $deepEnd->draw((new Arrow)->from('a')->to('b'));
        $deepEnd->draw((new Arrow)->from('d')->to('c'));
        $deepEnd->draw((new Arrow)->from('f')->to('e'));
        self::assertSame(['f', 'e', 'd', 'c', 'a', 'b'], $deepEnd->sort());
    }

    /**
     * @test
     */
    public function multiple_arrows_from_node_a_to_node_b_has_no_effect_on_sort_order(): void
    {
        $deepEnd = new DeepEnd();
        $deepEnd->add('a');
        $deepEnd->add('b');

        $deepEnd->draw((new Arrow)->from('a')->to('b'));
        $firstSort = $deepEnd->sort();
        $deepEnd->draw((new Arrow)->from('a')->to('b'));
        $secondSort = $deepEnd->sort();

        self::assertSame($firstSort, $secondSort);
    }

    /**
     * @test
     *
     * Note:
     * This is only true for the specific __construction__!
     * If we were to construct the same graph but with a different add and/or
     * draw order then the resulting sort order would be different.
     */
    public function sort_order_of_a_specific_graph_structure_is_deterministic(): void
    {
        $numberOfNodes = random_int(50, 200);
        $numberOfEdges = random_int(100, 1000);

        $deepEnd = new DeepEnd();

        $a = range(0, $numberOfNodes);
        shuffle($a);
        $a = array_map(function ($e) { return (string)$e; }, $a);
        foreach ($a as $e) {
            $deepEnd->add($e);
        }

        for ($i = 0; $i < $numberOfEdges; $i++) {
            try {
                $deepEnd->draw((new Arrow)->from($a[array_rand($a)])->to($a[array_rand($a)]));
            } catch (CycleDetected $e) {}
        }

        self::assertSame($deepEnd->sort(), $deepEnd->sort());
    }
}

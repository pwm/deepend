<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

use PHPUnit\Framework\TestCase;

class RandomSortTest extends TestCase
{
    /**
     * @test
     */
    public function normal_sort_of_an_acyclic_graph(): void
    {
        $expectedOrder = ['i', 'g', 'e', 'f', 'c', 'h', 'd', 'b', 'a'];
        static::assertSame($expectedOrder, $this->getDAG()->sort());
    }

    /**
     * @test
     */
    public function mapped_sort_of_an_acyclic_graph(): void
    {
        $expectedOrder = [
            'i' => 'i-data',
            'g' => 'g-data',
            'e' => 'e-data',
            'f' => 'f-data',
            'c' => 'c-data',
            'h' => 'h-data',
            'd' => 'd-data',
            'b' => 'b-data',
            'a' => 'a-data',
        ];
        static::assertSame($expectedOrder, $this->getDAG()->sortToMap());
    }

    /**
     * @test
     * @expectedException \Pwm\DeepEnd\Exception\CycleDetected
     */
    public function creating_a_cyclic_graph(): void
    {
        $deepEnd = new DeepEnd();

        $deepEnd->add('a');
        $deepEnd->add('b');
        $deepEnd->add('c');
        $deepEnd->add('d');
        $deepEnd->add('e');
        $deepEnd->add('f');
        $deepEnd->add('g');

        $deepEnd->draw((new Arrow)->from('a')->to('b'));
        $deepEnd->draw((new Arrow)->from('a')->to('c'));
        $deepEnd->draw((new Arrow)->from('a')->to('e'));
        $deepEnd->draw((new Arrow)->from('e')->to('d'));
        $deepEnd->draw((new Arrow)->from('e')->to('f'));
        $deepEnd->draw((new Arrow)->from('d')->to('f'));
        $deepEnd->draw((new Arrow)->from('f')->to('c'));
        $deepEnd->draw((new Arrow)->from('f')->to('g'));
        $deepEnd->draw((new Arrow)->from('g')->to('a'));
    }

    private function getDAG(): DeepEnd
    {
        $deepEnd = new DeepEnd();

        $deepEnd->add('a', 'a-data');
        $deepEnd->add('b', 'b-data');
        $deepEnd->add('c', 'c-data');
        $deepEnd->add('d', 'd-data');
        $deepEnd->add('e', 'e-data');
        $deepEnd->add('f', 'f-data');
        $deepEnd->add('g', 'g-data');
        $deepEnd->add('h', 'h-data');
        $deepEnd->add('i', 'i-data');

        $deepEnd->draw((new Arrow)->from('e')->to('f'));
        $deepEnd->draw((new Arrow)->from('c')->to('d'));
        $deepEnd->draw((new Arrow)->from('f')->to('d'));
        $deepEnd->draw((new Arrow)->from('h')->to('a'));
        $deepEnd->draw((new Arrow)->from('g')->to('h'));
        $deepEnd->draw((new Arrow)->from('c')->to('h'));
        $deepEnd->draw((new Arrow)->from('f')->to('b'));
        $deepEnd->draw((new Arrow)->from('f')->to('a'));
        $deepEnd->draw((new Arrow)->from('g')->to('f'));

        return $deepEnd;
    }
}

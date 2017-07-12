<?php
declare(strict_types=1);

namespace Pwm\DeepEnd;

use PHPUnit\Framework\TestCase;

class RandomSortTest extends TestCase
{
    /**
     * @test
     */
    public function random_graph_1(): void
    {
        $deepEnd = new DeepEnd();

        $deepEnd->add('a');
        $deepEnd->add('b');
        $deepEnd->add('c');
        $deepEnd->add('d');
        $deepEnd->add('e');
        $deepEnd->add('f');
        $deepEnd->add('g');
        $deepEnd->add('h');
        $deepEnd->add('i');

        $deepEnd->draw((new Arrow)->from('e')->to('f'));
        $deepEnd->draw((new Arrow)->from('c')->to('d'));
        $deepEnd->draw((new Arrow)->from('f')->to('d'));
        $deepEnd->draw((new Arrow)->from('h')->to('a'));
        $deepEnd->draw((new Arrow)->from('g')->to('h'));
        $deepEnd->draw((new Arrow)->from('c')->to('h'));
        $deepEnd->draw((new Arrow)->from('f')->to('b'));
        $deepEnd->draw((new Arrow)->from('f')->to('a'));
        $deepEnd->draw((new Arrow)->from('g')->to('f'));

        static::assertSame(['i', 'g', 'e', 'f', 'c', 'h', 'd', 'b', 'a'], $deepEnd->sort());
    }

    /**
     * @test
     * @expectedException \Pwm\DeepEnd\Exception\CycleDetected
     */
    public function random_graph_2(): void
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

        $deepEnd->sort();
    }
}

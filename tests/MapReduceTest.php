<?php
/**
 * Created by PhpStorm.
 * User: bomboze
 * Date: 17/04/2018
 * Time: 19:32
 */

namespace SlavaVishnyakov\MapReduce;

use PHPUnit\Framework\TestCase;

class MapReduceTest extends TestCase
{
    /** @test */
    public function works_1()
    {
        $m = new MapReduceProcess();
        $m->send('k', 1);
        $m->send('k', 2);

        $this->assertEquals(['k', [1, 2]], $m->next());
        $this->assertEquals(null, $m->next());
    }

    /** @test */
    public function works_2()
    {
        $m = new MapReduceProcess();
        $m->send('a', 1);
        $m->send('b', 1);
        $m->send('a', 2);

        $this->assertEquals(['a', [1, 2]], $m->next());
        $this->assertEquals(['b', [1]], $m->next());
        $this->assertEquals(null, $m->next());
    }

    /** @test */
    public function works_3()
    {
        $m = new MapReduceProcess();
        $m->send('a', 1);
        $m->send('b', 1);
        $m->send('c', ['c']);
        $m->send('a', 2);

        $this->assertEquals(['a', [1, 2]], $m->next());
        $this->assertEquals(['b', [1]], $m->next());
        $this->assertEquals(['c', [['c']]], $m->next());
        $this->assertEquals(null, $m->next());
    }

    /** @test */
    public function it_iterates()
    {
        $m = new MapReduceProcess();
        $m->send('a', 1);
        $m->send('b', 1);
        $m->send('c', 3);
        $m->send('a', 2);

        foreach($m->iter() as $key => $groups) {
            if($key == 'a') {
                $this->assertEquals([1,2], $groups);
            } elseif($key == 'b') {
                $this->assertEquals([1], $groups);
            } elseif($key == 'c') {
                $this->assertEquals([3], $groups);
            } else {
                $this->fail("Shouldn't be other keys");
            }
        }
    }
}

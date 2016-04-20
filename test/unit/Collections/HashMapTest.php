<?php
namespace Test\Unit;

use Asd\Collections\HashMap;

class HashMapTest extends \PHPUnit_Framework_TestCase
{
    protected $hashMap;

    public function setUp()
    {
        $this->hashMap = $this->getMockForAbstractClass('Asd\Collections\HashMap');
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::size
     */
    public function size()
    {
        $this->assertEquals(0, $this->hashMap->size());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::size
     */
    public function put()
    {
        $map = $this->hashMap->put('aKey', 'A value!');
        $this->assertEquals(0, $this->hashMap->size());
        $this->assertEquals(1, $map->size());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::size
     */
    public function put_overwrites()
    {
        $map = $this->hashMap->put('a', 1);
        $map = $map->put('a', 1);
        $this->assertEquals(1, $map->size());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::size
     */
    public function put_canChain()
    {
        $map = $this->hashMap->put('a', 1)->put('b', 1)->put('c', 1)->put('d', 1);
        $this->assertEquals(4, $map->size());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::size
     */
    public function put_canChain_overwrites()
    {
        $map = $this->hashMap->put('a', 1)->put('a', 2)->put('a', 3)->put('a', 4);
        $this->assertEquals(1, $map->size());
    }
}

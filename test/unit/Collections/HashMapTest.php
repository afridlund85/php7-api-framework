<?php
namespace Test\Unit;

use OutOfBoundsException;
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
     * @covers Asd\Collections\Collection::size
     * @covers Asd\Collections\Collection::isEmpty
     */
    public function size_and_isEmpty()
    {
        $this->assertEquals(0, $this->hashMap->size());
        $this->assertTrue($this->hashMap->isEmpty());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::containsKey
     * @covers Asd\Collections\Collection::size
     * @covers Asd\Collections\Collection::isEmpty
     */
    public function put()
    {
        $map = $this->hashMap->put('aKey', 'A value!');
        $this->assertEquals(0, $this->hashMap->size());
        $this->assertEquals(1, $map->size());
        $this->assertTrue($this->hashMap->isEmpty());
        $this->assertFalse($map->isEmpty());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::containsKey
     * @covers Asd\Collections\Collection::size
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
     * @covers Asd\Collections\HashMap::containsKey
     * @covers Asd\Collections\Collection::size
     */
    public function put_canChain()
    {
        $map = $this->hashMap->put('a', 1)->put('b', 1)->put('c', 1)->put('d', 1);
        $this->assertEquals(4, $map->size());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::containsKey
     * @covers Asd\Collections\Collection::size
     */
    public function put_canChain_overwrites()
    {
        $map = $this->hashMap->put('a', 1)->put('a', 2)->put('a', 3)->put('a', 4);
        $this->assertEquals(1, $map->size());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::get
     * @covers Asd\Collections\HashMap::containsKey
     * @covers Asd\Collections\Collection::size
     */
    public function get_singleValue()
    {
        $map = $this->hashMap->put('a', 1);
        $this->assertEquals(1, $map->get('a'));
        $this->assertEquals(1, $map->size());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::get
     * @covers Asd\Collections\HashMap::containsKey
     * @covers Asd\Collections\Collection::size
     */
    public function get_multipleValues()
    {
        $map = $this->hashMap->put('a', 1)->put('b', 2)->put('c', 3)->put('d', 4);
        $this->assertEquals(1, $map->get('a'));
        $this->assertEquals(2, $map->get('b'));
        $this->assertEquals(3, $map->get('c'));
        $this->assertEquals(4, $map->get('d'));
        $this->assertEquals(4, $map->size());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::get
     * @covers Asd\Collections\HashMap::containsKey
     */
    public function get_onEmpty()
    {
        $this->expectException(OutOfBoundsException::class);
        $this->hashMap->get('not-a-key');
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::get
     * @covers Asd\Collections\HashMap::containsKey
     */
    public function get_invalidKey()
    {
        $this->expectException(OutOfBoundsException::class);
        $map = $this->hashMap->put('a', 1)->put('b', 2);
        $map->get('c');
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::toArray
     * @covers Asd\Collections\HashMap::containsKey
     */
    public function toArray()
    {
        $map = $this->hashMap->put('a', 1)->put('b', 2);
        $this->assertEquals([1,2], $map->toArray());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::toArray
     */
    public function toArray_whenEmpty()
    {
        $this->assertEquals([], $this->hashMap->toArray());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::remove
     * @covers Asd\Collections\HashMap::toArray
     * @covers Asd\Collections\HashMap::containsKey
     * @covers Asd\Collections\Collection::size
     */
    public function remove()
    {
        $map = $this->hashMap->put('a', 1)->put('b', 2);
        $map = $map->remove('a');
        $this->assertEquals(0, $this->hashMap->size());
        $this->assertEquals(1, $map->size());
        $this->assertEquals([2], $map->toArray());
        $this->assertEquals(2, $map->get('b'));
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::remove
     * @covers Asd\Collections\HashMap::containsKey
     */
    public function remove_invalidKey()
    {
        $map = $this->hashMap->put('a', 1)->put('b', 2);
        $this->expectException(OutOfBoundsException::class);
        $map = $map->remove('c');
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::remove
     * @covers Asd\Collections\HashMap::containsKey
     */
    public function remove_whenEmpty()
    {
        $this->expectException(OutOfBoundsException::class);
        $map = $this->hashMap->remove('c');
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::clear
     * @covers Asd\Collections\HashMap::containsKey
     * @covers Asd\Collections\Collection::size
     */
    public function clear()
    {
        $map = $this->hashMap->put('a', 1)->put('b', 2)->put('c', 3)->put('d', 4);
        $clearedMap = $map->clear();
        $this->assertEquals(0, $clearedMap->size());
        $this->assertEquals([], $clearedMap->toArray());
        $this->assertEquals(4, $map->size());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::clear
     * @covers Asd\Collections\Collection::size
     */
    public function clear_whenEmpty()
    {
        $clearedMap = $this->hashMap->clear();
        $this->assertEquals(0, $clearedMap->size());
        $this->assertEquals([], $clearedMap->toArray());
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::containsKey
     */
    public function containsKey()
    {
        $this->assertFalse($this->hashMap->containsKey('not-there'));
        $map = $this->hashMap->put('a', 1);
        $this->assertFalse($map->containsKey('not-there'));
        $this->assertTrue($map->containsKey('a'));
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::put
     * @covers Asd\Collections\HashMap::containsValue
     */
    public function containsValue()
    {
        $map = $this->hashMap->put('a', 1);
        $this->assertFalse($this->hashMap->containsValue(1));
        $this->assertFalse($map->containsValue(2));
        $this->assertTrue($map->containsValue(1));
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::rewind
     * @covers Asd\Collections\IteratorTrait::rewind
     * @covers Asd\Collections\IteratorTrait::current
     * @covers Asd\Collections\IteratorTrait::key
     * @covers Asd\Collections\IteratorTrait::next
     * @covers Asd\Collections\IteratorTrait::valid
     */
    public function foreach_iteratorValueOnly()
    {
        $map = $this->hashMap->put('a', 1)->put('b', 2)->put('c', 3);
        $expected = [1,2,3];
        $iteratorCount = 0;
        foreach($map as $value){
            $iteratorCount++;
            $this->assertTrue(in_array($value, $expected));
        }
        $this->assertEquals(3, $iteratorCount);
    }

    /**
     * @test
     * @covers Asd\Collections\HashMap::rewind
     * @covers Asd\Collections\IteratorTrait::rewind
     * @covers Asd\Collections\IteratorTrait::current
     * @covers Asd\Collections\IteratorTrait::key
     * @covers Asd\Collections\IteratorTrait::next
     * @covers Asd\Collections\IteratorTrait::valid
     */
    public function foreach_iteratorKeyAndValue()
    {
        $map = $this->hashMap->put('a', 1)->put('b', 2)->put('c', 3);
        $expectedValues = [1,2,3];
        $expectedKeys = [0,1,2];
        $iteratorCount = 0;
        foreach($map as $key => $value){
            $iteratorCount++;
            $this->assertTrue(in_array($key, $expectedKeys));
            $this->assertTrue(in_array($value, $expectedValues));
        }
        $this->assertEquals(3, $iteratorCount);
    }
}

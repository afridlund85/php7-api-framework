<?php
namespace Test\Unit;

use OutOfRangeException;
use Asd\Collections\ArrayList;

class ArrayListTest extends \PHPUnit_Framework_TestCase
{
    protected $arrayList;

    public function setUp()
    {
        $this->arrayList = $this->getMockForAbstractClass('Asd\Collections\ArrayList');
    }

    /**
     * @test
     * @covers Asd\Collections\Collection::size
     * @covers Asd\Collections\Collection::isEmpty
     */
    public function size_and_isEmpty()
    {
        $this->assertEquals(0, $this->arrayList->size());
        $this->assertTrue($this->arrayList->isEmpty());
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\Collection::size
     * @covers Asd\Collections\Collection::isEmpty
     */
    public function add()
    {
        $list = $this->arrayList->add(1);
        $this->assertTrue($this->arrayList->isEmpty());
        $this->assertFalse($list->isEmpty());
        $this->assertEquals(1, $list->size());
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\Collection::size
     * @covers Asd\Collections\Collection::isEmpty
     */
    public function add_doesNotOverWrite()
    {
        $list = $this->arrayList->add(1);
        $list = $list->add(1);
        $list = $list->add(1);
        $this->assertFalse($list->isEmpty());
        $this->assertEquals(3, $list->size());
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\Collection::size
     */
    public function add_isChainable()
    {
        $list = $this->arrayList->add(1)->add(2)->add(3);
        $this->assertEquals(3, $list->size());
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::get
     * @covers Asd\Collections\Collection::size
     */
    public function get()
    {
        $list = $this->arrayList->add('a')->add('b')->add('c');
        $this->assertEquals('a', $list->get(0));
        $this->assertEquals('c', $list->get(2));
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::get
     */
    public function get_intvalidKey_tooLowIndex()
    {
        $list = $this->arrayList->add('a')->add('b')->add('c');
        $this->expectException(OutOfRangeException::class);
        $list->get(-1);
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::get
     */
    public function get_intvalidKey_tooHighIndex()
    {
        $list = $this->arrayList->add('a')->add('b')->add('c');
        $this->expectException(OutOfRangeException::class);
        $list->get(3);
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::addAt
     * @covers Asd\Collections\Collection::size
     */
    public function addAt_firstPosition()
    {
        $list = $this->arrayList->add('a')->add('b')->add('c');
        $list = $list->addAt('d', 0);
        $this->assertEquals(4, $list->size());
        $this->assertEquals('d', $list->get(0));
        $this->assertEquals('a', $list->get(1));
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::addAt
     * @covers Asd\Collections\Collection::size
     */
    public function addAt_middlePosition()
    {
        $list = $this->arrayList->add('a')->add('b')->add('c');
        $list = $list->addAt('d', 1);
        $this->assertEquals(4, $list->size());
        $this->assertEquals('a', $list->get(0));
        $this->assertEquals('d', $list->get(1));
        $this->assertEquals('b', $list->get(2));
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::addAt
     * @covers Asd\Collections\Collection::size
     */
    public function addAt_lastPosition()
    {
        $list = $this->arrayList->add('a')->add('b')->add('c');
        $list = $list->addAt('d', 2);
        $this->assertEquals(4, $list->size());
        $this->assertEquals('d', $list->get(2));
        $this->assertEquals('c', $list->get(3));
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::addAt
     * @covers Asd\Collections\ArrayList::get
     */
    public function addAt_intvalidKey_tooLowIndex()
    {
        $list = $this->arrayList->add('a')->add('b')->add('c');
        $this->expectException(OutOfRangeException::class);
        $list->addAt('d', -1);
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::addAt
     * @covers Asd\Collections\ArrayList::get
     */
    public function addAt_intvalidKey_tooHighIndex()
    {
        $list = $this->arrayList->add('a')->add('b')->add('c');
        $this->expectException(OutOfRangeException::class);
        $list->addAt('d', 3);
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::toArray
     */
    public function toArray()
    {
        $list = $this->arrayList->add(3)->add(5);
        $this->assertEquals([3,5], $list->toArray());
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::toArray
     */
    public function toArray_whenEmpty()
    {
        $this->assertEquals([], $this->arrayList->toArray());
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::remove
     * @covers Asd\Collections\ArrayList::toArray
     * @covers Asd\Collections\Collection::size
     */
    public function remove()
    {
        $list = $this->arrayList->add(3)->add(5);
        $list = $list->remove(0);
        $this->assertEquals(0, $this->arrayList->size());
        $this->assertEquals(1, $list->size());
        $this->assertEquals([5], $list->toArray());
        $this->assertEquals(5, $list->get(0));
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::remove
     */
    public function remove_invalidKey()
    {
        $list = $this->arrayList->add(3)->add(5);
        $this->expectException(OutOfRangeException::class);
        $list = $list->remove(4);
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::remove
     */
    public function remove_whenEmpty()
    {
        $this->expectException(OutOfRangeException::class);
        $list = $this->arrayList->remove(0);
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::clear
     * @covers Asd\Collections\ArrayList::toArray
     * @covers Asd\Collections\Collection::size
     */
    public function clear()
    {
        $list = $this->arrayList->add(3)->add(5)->add(7)->add(11);
        $clearedList = $list->clear();
        $this->assertEquals(0, $clearedList->size());
        $this->assertEquals([], $clearedList->toArray());
        $this->assertEquals(4, $list->size());
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::clear
     * @covers Asd\Collections\ArrayList::toArray
     * @covers Asd\Collections\Collection::size
     */
    public function clear_whenEmpty()
    {
        $list = $this->arrayList->clear();
        $this->assertEquals(0, $list->size());
        $this->assertEquals([], $list->toArray());
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::contains
     */
    public function containsMethod()
    {
        $list = $this->arrayList->add(3)->add(5);
        $this->assertFalse($this->arrayList->contains(3));
        $this->assertFalse($list->contains(7));
        $this->assertTrue($list->contains(3));
        $this->assertTrue($list->contains(5));
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::add
     * @covers Asd\Collections\ArrayList::indexOf
     */
    public function indexOf()
    {
        $list = $this->arrayList->add(3)->add(5);
        $this->assertEquals(-1, $this->arrayList->indexOf(3));
        $this->assertEquals(0, $list->indexOf(3));
        $this->assertEquals(1, $list->indexOf(5));
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::rewind
     * @covers Asd\Collections\IteratorTrait::rewind
     * @covers Asd\Collections\IteratorTrait::current
     * @covers Asd\Collections\IteratorTrait::key
     * @covers Asd\Collections\IteratorTrait::next
     * @covers Asd\Collections\IteratorTrait::valid
     */
    public function foreach_iteratorValueOnly()
    {
        $list = $this->arrayList->add(3)->add(5)->add(7);
        $expected = [3,5,7];
        $iteratorCount = 0;
        foreach($list as $value){
            $iteratorCount++;
            $this->assertTrue(in_array($value, $expected));
        }
        $this->assertEquals(3, $iteratorCount);
    }

    /**
     * @test
     * @covers Asd\Collections\ArrayList::rewind
     * @covers Asd\Collections\IteratorTrait::rewind
     * @covers Asd\Collections\IteratorTrait::current
     * @covers Asd\Collections\IteratorTrait::key
     * @covers Asd\Collections\IteratorTrait::next
     * @covers Asd\Collections\IteratorTrait::valid
     */
    public function foreach_iteratorKeyAndValue()
    {
        $list = $this->arrayList->add(3)->add(5)->add(7);
        $expectedValues = [3,5,7];
        $expectedKeys = [0,1,2];
        $iteratorCount = 0;
        foreach($list as $key => $value){
            $iteratorCount++;
            $this->assertTrue(in_array($key, $expectedKeys));
            $this->assertTrue(in_array($value, $expectedValues));
        }
        $this->assertEquals(3, $iteratorCount);
    }
}
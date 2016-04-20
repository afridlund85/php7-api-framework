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
     */
    public function size()
    {
        $this->assertEquals(0, $this->hashMap->size());
    }
}
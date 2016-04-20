<?php
namespace Test\Unit;

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
     */
    public function size()
    {
        $this->assertEquals(0, $this->arrayList->size());
    }
}
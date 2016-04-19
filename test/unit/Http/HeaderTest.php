<?php
namespace Test\Unit;

use Asd\Http\Header;

class HeaderTest extends \PHPUnit_Framework_TestCase
{
    protected $header;
    
    public function setUp()
    {
        $this->header = new Header('name', ['value1', 'value2']);
    }

    /**
     * @test
     * @covers Asd\Http\Header::__construct
     * @covers Asd\Http\Header::validateValues
     * @expectedException InvalidArgumentException
     */
    public function constructor_withInvalidValue()
    {
        new Header('someName', [1]);
    }

    /**
     * @test
     * @covers Asd\Http\Header::getName
     */
    public function _getName()
    {
        $this->assertEquals('name', $this->header->getName());
    }

    /**
     * @test
     * @covers Asd\Http\Header::getValues
     */
    public function getValues()
    {
        $this->assertEquals(['value1', 'value2'], $this->header->getValues());
    }

    /**
     * @test
     * @covers Asd\Http\Header::getHeaderLine
     */
    public function getHeaderLine()
    {
        $this->assertEquals('value1, value2', $this->header->getHeaderLine());
    }

    /**
     * @test
     * @covers Asd\Http\Header::__toString
     */
    public function toString()
    {
        $this->assertEquals('name: value1, value2', (string)$this->header);
    }

    /**
     * @test
     * @covers Asd\Http\Header::withName
     * @covers Asd\Http\Header::getName
     */
    public function withHeaderName()
    {
        $header = $this->header->withName('newName');
        $this->assertEquals('name', $this->header->getName());
        $this->assertEquals('newName', $header->getName());
        $this->assertNotSame($this->header, $header);
    }

    /**
     * @test
     * @covers Asd\Http\Header::withValues
     * @covers Asd\Http\Header::getValues
     * @covers Asd\Http\Header::validateValues
     */
    public function withValues()
    {
        $header = $this->header->withValues(['newValue']);
        $this->assertEquals(['value1', 'value2'], $this->header->getValues());
        $this->assertEquals(['newValue'], $header->getValues());
        $this->assertNotSame($this->header, $header);
    }

    /**
     * @test
     * @covers Asd\Http\Header::withValues
     * @covers Asd\Http\Header::validateValues
     * @expectedException InvalidArgumentException
     */
    public function withValues_invalidValues()
    {
        $header = $this->header->withValues([1, "two", 3]);
    }

    /**
     * @test
     * @covers Asd\Http\Header::withAddedValues
     * @covers Asd\Http\Header::getValues
     * @covers Asd\Http\Header::validateValues
     */
    public function withAddedValues()
    {
        $header = $this->header->withAddedValues(['newValue']);
        $this->assertEquals(['value1', 'value2'], $this->header->getValues());
        $this->assertEquals(['value1', 'value2', 'newValue'], $header->getValues());
        $this->assertNotSame($this->header, $header);
    }

    /**
     * @test
     * @covers Asd\Http\Header::withAddedValues
     * @covers Asd\Http\Header::validateValues
     * @expectedException InvalidArgumentException
     */
    public function withAddedValues_invalidValues()
    {
        $header = $this->header->withAddedValues([1, "two", 3]);
    }
}
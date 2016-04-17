<?php
namespace Test\Unit;

use Asd\Http\HttpHeader;

class HttpHeaderTest extends \PHPUnit_Framework_TestCase
{
    protected $httpHeader;
    
    public function setUp()
    {
        $this->httpHeader = new HttpHeader('name', ['value1', 'value2']);
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeader::__construct
     * @covers Asd\Http\HttpHeader::filterValues
     * @expectedException InvalidArgumentException
     */
    public function constructor_withInvalidValue()
    {
        new HttpHeader('someName', [1]);
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeader::getHeaderName
     */
    public function getHeaderName()
    {
        $this->assertEquals('name', $this->httpHeader->getHeaderName());
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeader::getValues
     */
    public function getValues()
    {
        $this->assertEquals(['value1', 'value2'], $this->httpHeader->getValues());
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeader::getHeaderLine
     */
    public function getHeaderLine()
    {
        $this->assertEquals('value1, value2', $this->httpHeader->getHeaderLine());
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeader::__toString
     */
    public function toString()
    {
        $this->assertEquals('name: value1, value2', (string)$this->httpHeader);
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeader::withHeaderName
     * @covers Asd\Http\HttpHeader::getHeaderName
     */
    public function withHeaderName()
    {
        $httpHeader = $this->httpHeader->withHeaderName('newName');
        $this->assertEquals('name', $this->httpHeader->getHeaderName());
        $this->assertEquals('newName', $httpHeader->getHeaderName());
        $this->assertNotSame($this->httpHeader, $httpHeader);
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeader::withValues
     * @covers Asd\Http\HttpHeader::getValues
     * @covers Asd\Http\HttpHeader::filterValues
     */
    public function withValues()
    {
        $httpHeader = $this->httpHeader->withValues(['newValue']);
        $this->assertEquals(['value1', 'value2'], $this->httpHeader->getValues());
        $this->assertEquals(['newValue'], $httpHeader->getValues());
        $this->assertNotSame($this->httpHeader, $httpHeader);
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeader::withValues
     * @covers Asd\Http\HttpHeader::filterValues
     * @expectedException InvalidArgumentException
     */
    public function withValues_invalidValues()
    {
        $httpHeader = $this->httpHeader->withValues([1, "two", 3]);
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeader::withAddedValues
     * @covers Asd\Http\HttpHeader::getValues
     * @covers Asd\Http\HttpHeader::filterValues
     */
    public function withAddedValues()
    {
        $httpHeader = $this->httpHeader->withAddedValues(['newValue']);
        $this->assertEquals(['value1', 'value2'], $this->httpHeader->getValues());
        $this->assertEquals(['value1', 'value2', 'newValue'], $httpHeader->getValues());
        $this->assertNotSame($this->httpHeader, $httpHeader);
    }

    /**
     * @test
     * @covers Asd\Http\HttpHeader::withAddedValues
     * @covers Asd\Http\HttpHeader::filterValues
     * @expectedException InvalidArgumentException
     */
    public function withAddedValues_invalidValues()
    {
        $httpHeader = $this->httpHeader->withAddedValues([1, "two", 3]);
    }
}
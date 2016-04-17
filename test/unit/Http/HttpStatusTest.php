<?php
namespace Test\Unit;

use InvalidArgumentException;
use Asd\Http\HttpStatus;

class HttpStatusTest extends \PHPUnit_Framework_TestCase
{
    protected $httpStatus;
    
    public function setUp()
    {
        $this->httpStatus = new HttpStatus(200, 'Okay');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function constructor_withTooLowStatusCode()
    {
        new HttpStatus(99, 'Strange');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function constructor_withHighLowStatusCode()
    {
        new HttpStatus(600, 'Strange');
    }

    /**
     * @test
     * @covers Asd\Http\HttpStatus::getStatusCode
     */
    public function getStatusCode()
    {
        $this->assertEquals(200, $this->httpStatus->getStatusCode());
    }

    /**
     * @test
     * @covers Asd\Http\HttpStatus::getPhrase
     */
    public function getPhrase()
    {
        $this->assertEquals('Okay', $this->httpStatus->getPhrase());
    }

    /**
     * @test
     * @covers Asd\Http\HttpStatus::withStatusCode
     */
    public function withStatusCode()
    {
        $httpStatus = $this->httpStatus->withStatusCode(201);
        $this->assertEquals(201, $httpStatus->getStatusCode());
        $this->assertNotSame($httpStatus, $this->httpStatus);
    }

    /**
     * @test
     * @covers Asd\Http\HttpStatus::withStatusCode
     * @expectedException InvalidArgumentException
     */
    public function withStatusCode_invalidValue()
    {
        $this->httpStatus->withStatusCode(99);
    }

    /**
     * @test
     * @covers Asd\Http\HttpStatus::withPhrase
     */
    public function withPhrase()
    {
        $httpStatus = $this->httpStatus->withPhrase('OK');
        $this->assertEquals('OK', $httpStatus->getPhrase());
        $this->assertNotSame($httpStatus, $this->httpStatus);
    }
}
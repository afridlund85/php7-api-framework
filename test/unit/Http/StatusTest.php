<?php
namespace Test\Unit;

use InvalidArgumentException;
use Asd\Http\Status;

class StatusTest extends \PHPUnit_Framework_TestCase
{
    protected $status;
    
    public function setUp()
    {
        $this->status = new Status(200, 'Okay');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function constructor_withTooLowStatusCode()
    {
        new Status(99, 'Under 100');
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function constructor_withHighLowStatusCode()
    {
        new Status(600, 'Over 599');
    }

    /**
     * @test
     * @covers Asd\Http\Status::getCode
     */
    public function getStatusCode()
    {
        $this->assertEquals(200, $this->status->getCode());
    }

    /**
     * @test
     * @covers Asd\Http\Status::getPhrase
     */
    public function getPhrase()
    {
        $this->assertEquals('Okay', $this->status->getPhrase());
    }

    /**
     * @test
     * @covers Asd\Http\Status::withCode
     */
    public function withStatusCode()
    {
        $newStatus = $this->status->withCode(201);
        $this->assertEquals(201, $newStatus->getCode());
        $this->assertNotSame($newStatus, $this->status);
    }

    /**
     * @test
     * @covers Asd\Http\Status::withCode
     * @expectedException InvalidArgumentException
     */
    public function withStatusCode_invalidValue()
    {
        $this->status->withCode(99);
    }

    /**
     * @test
     * @covers Asd\Http\Status::withPhrase
     */
    public function withPhrase()
    {
        $newStatus = $this->status->withPhrase('OK');
        $this->assertEquals('OK', $newStatus->getPhrase());
        $this->assertNotSame($newStatus, $this->status);
    }
}
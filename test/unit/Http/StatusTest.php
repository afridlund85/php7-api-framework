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
     * @covers Asd\Http\Status::getStatusCode
     */
    public function getStatusCode()
    {
        $this->assertEquals(200, $this->status->getStatusCode());
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
     * @covers Asd\Http\Status::withStatusCode
     */
    public function withStatusCode()
    {
        $newStatus = $this->status->withStatusCode(201);
        $this->assertEquals(201, $newStatus->getStatusCode());
        $this->assertNotSame($newStatus, $this->status);
    }

    /**
     * @test
     * @covers Asd\Http\Status::withStatusCode
     * @expectedException InvalidArgumentException
     */
    public function withStatusCode_invalidValue()
    {
        $this->status->withStatusCode(99);
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
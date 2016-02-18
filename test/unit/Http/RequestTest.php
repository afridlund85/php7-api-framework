<?php
namespace Test\Unit;

use Asd\Http\Request;
use Asd\Http\Message;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\MessageInterface;

class RequestTest extends \PHPUnit_Framework_TestCase
{

  protected $request;

  /**
   * @before
   */
  public function setup()
  {
    $this->request = new Request();
  }
  /**
   * @test
   */
  public function implements_PSR7()
  {
    
    $this->assertInstanceOf('Asd\Http\Message', $this->request);
    $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $this->request);
    $this->assertInstanceOf('Psr\Http\Message\RequestInterface', $this->request);
  }

  /**
   * @test
   * @covers Asd\Http\Message::getProtocolVersion
   */
  public function getProtocolVersion_returnStringValue()
  {
    $this->assertSame($this->request->getProtocolVersion(), '1.1');
  }

  /**
   * @test
   * @covers Asd\Http\Message::withProtocolVersion
   */
  public function withProtocolVersion_returnMutatedClone()
  {
    $clone = $this->request->withProtocolVersion('1.0');

    $this->assertSame($clone->getProtocolVersion(), '1.0');
    $this->assertSame($this->request->getProtocolVersion(), '1.1');
    $this->assertNotEquals($this->request, $clone);
    $this->assertNotSame($this->request, $clone);
  }

  /**
   * @test
   * @covers Asd\Http\Message::withProtocolVersion
   */
  public function withProtocolVersion_convertsNumericParamToStringValue()
  {
    $clone1 = $this->request->withProtocolVersion(1.0);
    $clone2 = $this->request->withProtocolVersion(1.1);
    $this->assertSame($clone1->getProtocolVersion(), '1.0');
    $this->assertSame($clone2->getProtocolVersion(), '1.1');
  }

  /**
   * @test
   * @covers Asd\Http\Message::getHeaders
   */
  public function getHeaders_returnsArrayOfHeaders()
  {
    $this->assertEquals($this->request->getHeaders(), []);
  }
}